<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\MagangApplication;
use App\Models\MagangMember;
use App\Models\ApplicationDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PengajuanMagangController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Cari pengajuan di mana user bertindak sebagai ketua atau anggota
        $pengajuan = $user->latestMagangApplication();

        if ($pengajuan) {
            $pengajuan->load(['members.user', 'documents', 'dplAssignment.dpl']);
        }

        // Daftar mahasiswa di prodi yang sama untuk opsi pemilihan anggota kelompok
        $mahasiswaProdi = User::where('role', 'mahasiswa')
            ->where('prodi_id', $user->prodi_id)
            ->where('id', '!=', $user->id)
            ->where('is_active', true)
            ->get();

        return view('mahasiswa.pengajuan.index', compact('pengajuan', 'mahasiswaProdi'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->total_sks <= 0 || $user->ipk <= 0) {
            return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi data SKS dan IPK di profil Anda terlebih dahulu sebelum mengajukan magang.');
        }

        if ($user->total_sks < 100 || $user->ipk < 2.50) {
            return redirect()->back()->with('error', 'Maaf, Anda tidak memenuhi syarat akademik untuk mengajukan magang. Syarat minimal adalah 100 SKS dan IPK 2.50.');
        }

        $request->validate([
            'is_group' => 'required|in:0,1',
            'jenis_magang' => 'required|in:prodi,mandiri',
            'instansi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'leader_ktm' => 'required|mimes:pdf,jpg,png|max:2048',
            'leader_transkrip' => 'required|mimes:pdf,jpg,png|max:2048',
            'member_user_ids' => 'nullable|array|max:3',
            'member_user_ids.*' => 'exists:users,id',
            'member_ktm.*' => 'nullable|mimes:pdf,jpg,png|max:2048',
            'member_transkrip.*' => 'nullable|mimes:pdf,jpg,png|max:2048',
        ], [
            'leader_ktm.required' => 'KTM ketua/pemohon wajib diunggah.',
            'leader_transkrip.required' => 'Transkrip nilai ketua/pemohon wajib diunggah.',
            'member_user_ids.max' => 'Jumlah anggota kelompok maksimal 3 orang (total 4 orang termasuk ketua).',
        ]);

        $isGroup = (bool) $request->is_group;

        $pengajuan = MagangApplication::create([
            'user_id' => $user->id,
            'prodi_id' => $user->prodi_id,
            'is_group' => $isGroup,
            'jenis_magang' => $request->jenis_magang,
            'instansi' => $request->instansi,
            'alamat' => $request->alamat,
            'alamat_instansi' => $request->alamat,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending',
        ]);

        // Rekord Anggota: Ketua
        MagangMember::create([
            'application_id' => $pengajuan->id,
            'user_id' => $user->id,
            'is_leader' => true,
        ]);

        // Simpan Berkas KTM & Transkrip Ketua
        if ($request->hasFile('leader_ktm')) {
            $pathKtm = $request->file('leader_ktm')->store('dokumen_pengajuan', 'public');
            ApplicationDocument::create([
                'application_id' => $pengajuan->id,
                'user_id' => $user->id,
                'type' => 'KTM',
                'file_path' => $pathKtm,
            ]);
        }

        if ($request->hasFile('leader_transkrip')) {
            $pathTranskrip = $request->file('leader_transkrip')->store('dokumen_pengajuan', 'public');
            ApplicationDocument::create([
                'application_id' => $pengajuan->id,
                'user_id' => $user->id,
                'type' => 'TRANSKRIP',
                'file_path' => $pathTranskrip,
            ]);
        }

        // Jika Kelompok: Simpan Anggota dan Dokumennya
        if ($isGroup && $request->has('member_user_ids')) {
            foreach ($request->member_user_ids as $index => $memberId) {
                if ($memberId && $memberId != $user->id) {
                    MagangMember::create([
                        'application_id' => $pengajuan->id,
                        'user_id' => $memberId,
                        'is_leader' => false,
                    ]);

                    // Dokumen KTM Anggota
                    if ($request->hasFile("member_ktm.{$index}")) {
                        $pathMemberKtm = $request->file("member_ktm.{$index}")->store('dokumen_pengajuan', 'public');
                        ApplicationDocument::create([
                            'application_id' => $pengajuan->id,
                            'user_id' => $memberId,
                            'type' => 'KTM',
                            'file_path' => $pathMemberKtm,
                        ]);
                    }

                    // Dokumen Transkrip Anggota
                    if ($request->hasFile("member_transkrip.{$index}")) {
                        $pathMemberTranskrip = $request->file("member_transkrip.{$index}")->store('dokumen_pengajuan', 'public');
                        ApplicationDocument::create([
                            'application_id' => $pengajuan->id,
                            'user_id' => $memberId,
                            'type' => 'TRANSKRIP',
                            'file_path' => $pathMemberTranskrip,
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Pengajuan magang berhasil dikirim. Silakan unduh Surat Pengantar dari Program Studi untuk dimintakan tanda tangan manual.');
    }

    public function cetakSuratPengantar(string $id)
    {
        $pengajuan = MagangApplication::with(['user.prodi', 'members.user.prodi'])->findOrFail($id);

        $pdf = Pdf::loadView('mahasiswa.pengajuan.surat_pengantar', compact('pengajuan'));
        $pdf->setPaper('A4', 'portrait');

        $namaFile = 'Surat_Pengantar_Magang_' . ($pengajuan->user->nim ?? 'Kelompok') . '.pdf';

        return $pdf->stream($namaFile);
    }

    public function uploadSuratBalasan(Request $request, string $id)
    {
        $request->validate([
            'surat_balasan' => 'required|mimes:pdf,jpg,png|max:2048',
        ], [
            'surat_balasan.required' => 'File surat balasan/penerimaan wajib diunggah.',
            'surat_balasan.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $pengajuan = MagangApplication::findOrFail($id);

        // Hanya ketua atau anggota yang boleh mengunggah
        $isMember = $pengajuan->members()->where('user_id', $request->user()->id)->exists() || $pengajuan->user_id == $request->user()->id;
        if (!$isMember) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        if ($request->hasFile('surat_balasan')) {
            $path = $request->file('surat_balasan')->store('dokumen_pengajuan', 'public');

            ApplicationDocument::create([
                'application_id' => $pengajuan->id,
                'user_id' => $request->user()->id,
                'type' => 'SURAT_PENERIMAAN',
                'file_path' => $path,
            ]);

            return redirect()->back()->with('success', 'Surat balasan instansi berhasil diunggah. Menunggu penetapan DPL oleh Admin Program Studi.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file.');
    }

    public function downloadTemplateLaporan()
    {
        $content = "PEDOMAN DAN TEMPLATE LAPORAN AKHIR MAGANG\nPROGRAM STUDI TEKNOLOGI INFORMASI\n\n1. HALAMAN JUDUL\n2. LEMBAR PENGESAHAN DPL & INSTANSI\n3. KATA PENGANTAR\n4. DAFTAR ISI\n5. BAB I: PENDAHULUAN (Latar Belakang, Tujuan, Manfaat)\n6. BAB II: GAMBARAN UMUM INSTANSI\n7. BAB III: PELAKSANAAN MAGANG & PEMBAHASAN\n8. BAB IV: PENUTUP (Kesimpulan & Saran)\n9. LAMPIRAN (Logbook, Rekap Presensi, Dokumentasi)";

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="Template_Laporan_Magang.txt"');
    }

    public function uploadLaporanAkhir(Request $request, string $id)
    {
        $request->validate([
            'laporan_akhir' => 'required|mimes:pdf|max:10240',
        ], [
            'laporan_akhir.required' => 'File laporan akhir magang wajib diunggah dalam format PDF.',
            'laporan_akhir.max' => 'Ukuran laporan maksimal 10MB.',
        ]);

        $pengajuan = MagangApplication::findOrFail($id);

        $isMember = $pengajuan->members()->where('user_id', $request->user()->id)->exists() || $pengajuan->user_id == $request->user()->id;
        if (!$isMember) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        if ($request->hasFile('laporan_akhir')) {
            $path = $request->file('laporan_akhir')->store('laporan_magang', 'public');

            ApplicationDocument::create([
                'application_id' => $pengajuan->id,
                'user_id' => $request->user()->id,
                'type' => 'LAPORAN_AKHIR',
                'file_path' => $path,
            ]);

            return redirect()->back()->with('success', 'Laporan akhir magang berhasil diunggah.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah laporan akhir.');
    }
}
