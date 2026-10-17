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
            $pengajuan->load(['members.user', 'documents', 'dplAssignment.dpl', 'evaluation.dpl']);
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

        if ($user->total_sks < 110 || $user->ipk < 2.75) {
            return redirect()->back()->with('error', 'Maaf, Anda tidak memenuhi syarat akademik untuk mengajukan magang. Syarat minimal adalah 110 SKS (setara semester 6 ke atas) dan IPK 2.75.');
        }

        // Sanitasi dan pastikan data anggota kelompok bersih
        $isGroup = $request->jenis_magang === 'prodi' && $request->is_group == '1';

        if (!$isGroup) {
            $request->merge(['member_user_ids' => null, 'is_group' => '0']);
        } else {
            // Bersihkan string kosong jika slot anggota tidak dipilih
            $rawMembers = $request->input('member_user_ids', []);
            $cleanMembers = is_array($rawMembers)
                ? array_values(array_filter($rawMembers, fn($id) => !empty($id)))
                : [];
            $request->merge(['member_user_ids' => !empty($cleanMembers) ? $cleanMembers : null]);
        }

        $request->validate([
            'is_group' => 'required|in:0,1',
            'jenis_magang' => 'required|in:prodi,mandiri',
            'instansi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'leader_ktm' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'leader_transkrip' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'leader_spp' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'bukti_magang_mandiri' => 'nullable|mimes:pdf,jpg,jpeg,png|max:10240',
            'member_user_ids' => 'nullable|array|max:3',
            'member_user_ids.*' => 'required|exists:users,id',
            'member_ktm.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
            'member_transkrip.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
            'member_spp.*' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'instansi.required' => 'Nama instansi / perusahaan tujuan wajib diisi.',
            'alamat.required' => 'Alamat lengkap instansi wajib diisi.',
            'start_date.required' => 'Tanggal mulai magang wajib diisi.',
            'end_date.required' => 'Tanggal selesai magang wajib diisi.',
            'end_date.after' => 'Tanggal selesai magang harus setelah tanggal mulai magang.',
            'leader_ktm.required' => 'KTM ketua/pemohon wajib diunggah.',
            'leader_ktm.mimes' => 'Format KTM harus berupa PDF, JPG, JPEG, atau PNG.',
            'leader_ktm.max' => 'Ukuran berkas KTM maksimal 5MB.',
            'leader_transkrip.required' => 'Transkrip nilai ketua/pemohon wajib diunggah.',
            'leader_transkrip.mimes' => 'Format transkrip harus berupa PDF, JPG, JPEG, atau PNG.',
            'leader_transkrip.max' => 'Ukuran berkas transkrip nilai maksimal 5MB.',
            'leader_spp.required' => 'Slip pembayaran SPP/UKT terakhir pemohon wajib diunggah.',
            'leader_spp.mimes' => 'Format slip SPP harus berupa PDF, JPG, JPEG, atau PNG.',
            'leader_spp.max' => 'Ukuran berkas slip SPP maksimal 5MB.',
            'member_user_ids.max' => 'Jumlah anggota kelompok maksimal 3 orang (total 4 orang termasuk ketua).',
            'member_user_ids.*.exists' => 'Mahasiswa anggota yang dipilih tidak valid atau tidak terdaftar.',
            'bukti_magang_mandiri.mimes' => 'Format bukti magang mandiri harus berupa PDF, JPG, JPEG, atau PNG.',
            'bukti_magang_mandiri.max' => 'Ukuran berkas bukti magang mandiri maksimal 10MB.',
        ]);

        $prodiId = $user->prodi_id ?: (\App\Models\Prodi::where('kode_prodi', 'SI')->value('id') ?? \App\Models\Prodi::value('id'));
        if (!$user->prodi_id && $prodiId) {
            $user->update(['prodi_id' => $prodiId]);
        }

        $pengajuan = MagangApplication::create([
            'user_id' => $user->id,
            'prodi_id' => $prodiId,
            'is_group' => $isGroup,
            'jenis_magang' => $request->jenis_magang,
            'instansi' => $request->instansi,
            'alamat' => $request->alamat,
            'alamat_instansi' => $request->alamat,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending',
        ]);

        // Rekord Anggota: Pemohon Tunggal / Ketua
        MagangMember::create([
            'application_id' => $pengajuan->id,
            'user_id' => $user->id,
            'is_leader' => true,
        ]);

        // Simpan Berkas KTM, Transkrip & Slip SPP Ketua
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

        if ($request->hasFile('leader_spp')) {
            $pathSpp = $request->file('leader_spp')->store('dokumen_pengajuan', 'public');
            ApplicationDocument::create([
                'application_id' => $pengajuan->id,
                'user_id' => $user->id,
                'type' => 'SLIP_SPP',
                'file_path' => $pathSpp,
            ]);
        }

        // Jika Magang Mandiri dan ada berkas bukti yang disertakan langsung
        if ($request->jenis_magang === 'mandiri' && $request->hasFile('bukti_magang_mandiri')) {
            $pathBukti = $request->file('bukti_magang_mandiri')->store('bukti_mandiri', 'public');
            ApplicationDocument::create([
                'application_id' => $pengajuan->id,
                'user_id' => $user->id,
                'type' => 'BUKTI_MAGANG_MANDIRI',
                'file_path' => $pathBukti,
            ]);
        }

        // Jika Kelompok (Khusus Jalur Prodi): Simpan Anggota dan Dokumennya
        if ($isGroup && is_array($request->member_user_ids)) {
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

                    // Dokumen Slip SPP Anggota
                    if ($request->hasFile("member_spp.{$index}")) {
                        $pathMemberSpp = $request->file("member_spp.{$index}")->store('dokumen_pengajuan', 'public');
                        ApplicationDocument::create([
                            'application_id' => $pengajuan->id,
                            'user_id' => $memberId,
                            'type' => 'SLIP_SPP',
                            'file_path' => $pathMemberSpp,
                        ]);
                    }
                }
            }
        }

        $pesanSukses = $request->jenis_magang === 'mandiri'
            ? 'Pendaftaran magang mandiri berhasil dikirim. Anda dapat mengunggah atau memperbarui bukti berkas magang mandiri untuk dinilai DPL.'
            : 'Pengajuan magang berhasil dikirim. Silakan unduh Surat Pengantar dari Program Studi untuk dimintakan tanda tangan manual.';

        return redirect()->route('mahasiswa.pengajuan.index')->with('success', $pesanSukses);
    }

    public function cetakSuratPengantar(string $id)
    {
        $pengajuan = MagangApplication::with(['user.prodi', 'members.user.prodi'])->findOrFail($id);

        if ($pengajuan->status === 'pending') {
            return redirect()->back()->with('error', 'Surat Pengantar belum dapat diunduh karena pengajuan masih dalam proses verifikasi oleh Admin Program Studi.');
        }

        $pdf = Pdf::loadView('mahasiswa.pengajuan.surat_pengantar', compact('pengajuan'));
        $pdf->setPaper('A4', 'portrait');

        $namaFile = 'Surat_Pengantar_Magang_' . ($pengajuan->user->nim ?? 'Kelompok') . '.pdf';

        return $pdf->stream($namaFile);
    }

    public function uploadSuratBalasan(Request $request, string $id)
    {
        $pengajuan = MagangApplication::findOrFail($id);

        if ($pengajuan->status === 'pending') {
            return redirect()->back()->with('error', 'Surat Balasan belum dapat diunggah karena pengajuan masih dalam proses verifikasi oleh Admin Program Studi.');
        }

        // Hanya ketua atau anggota yang boleh mengunggah
        $isMember = $pengajuan->members()->where('user_id', $request->user()->id)->exists() || $pengajuan->user_id == $request->user()->id;
        if (!$isMember) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        $request->validate([
            'surat_balasan' => 'required|mimes:pdf,jpg,png|max:2048',
        ], [
            'surat_balasan.required' => 'File surat balasan/penerimaan wajib diunggah.',
            'surat_balasan.max' => 'Ukuran file maksimal 2MB.',
        ]);

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

    public function uploadBuktiMandiri(Request $request, string $id)
    {
        $request->validate([
            'bukti_magang' => 'required|mimes:pdf,jpg,png|max:10240',
        ], [
            'bukti_magang.required' => 'File bukti magang mandiri wajib diunggah.',
            'bukti_magang.max' => 'Ukuran file maksimal 10MB.',
        ]);

        $pengajuan = MagangApplication::findOrFail($id);

        $userId = $request->user()->id;
        $isAuthorized = $pengajuan->user_id == $userId || $pengajuan->members()->where('user_id', $userId)->exists();
        if (!$isAuthorized) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        if ($request->hasFile('bukti_magang')) {
            $path = $request->file('bukti_magang')->store('bukti_mandiri', 'public');

            ApplicationDocument::create([
                'application_id' => $pengajuan->id,
                'user_id' => $userId,
                'type' => 'BUKTI_MAGANG_MANDIRI',
                'file_path' => $path,
            ]);

            return redirect()->back()->with('success', 'Berkas bukti magang mandiri berhasil diunggah. DPL dapat meninjau berkas ini untuk memberikan penilaian.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file bukti magang mandiri.');
    }

    public function cetakLembarNilai(string $id)
    {
        $application = MagangApplication::with(['user.prodi', 'members.user.prodi', 'dplAssignment.dpl', 'evaluation'])->findOrFail($id);

        $userId = auth()->id();
        $isAuthorized = $application->user_id == $userId || $application->members()->where('user_id', $userId)->exists();
        if (!$isAuthorized) {
            abort(403, 'Akses ditolak.');
        }

        if (!$application->evaluation) {
            return redirect()->back()->with('error', 'Penilaian belum diterbitkan oleh DPL untuk pengajuan ini.');
        }

        $pdf = Pdf::loadView('dpl.evaluasi.lembar_penilaian', compact('application'));
        $pdf->setPaper('A4', 'portrait');

        $namaMahasiswa = $application->user->name ?? 'Mahasiswa';
        $safeName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $namaMahasiswa);

        return $pdf->stream('Lembar_Penilaian_Magang_' . $safeName . '.pdf');
    }

    public function downloadTemplateLaporan()
    {
        $filePath = public_path('doc/Panduan KKN TEMATIK MAGANG.docx');
        if (file_exists($filePath)) {
            return response()->download($filePath, 'Panduan_dan_Template_Laporan_Magang_SI.docx');
        }

        $content = "PEDOMAN DAN TEMPLATE LAPORAN AKHIR MAGANG\nPROGRAM STUDI SISTEM INFORMASI\n\n1. HALAMAN JUDUL\n2. LEMBAR PENGESAHAN DPL & INSTANSI\n3. KATA PENGANTAR\n4. DAFTAR ISI\n5. BAB I: PENDAHULUAN (Latar Belakang, Tujuan, Manfaat)\n6. BAB II: GAMBARAN UMUM INSTANSI\n7. BAB III: PELAKSANAAN MAGANG & PEMBAHASAN\n8. BAB IV: PENUTUP (Kesimpulan & Saran)\n9. LAMPIRAN (Logbook, Rekap Presensi, Dokumentasi)";

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="Template_Laporan_Magang.txt"');
    }

    public function downloadFormPenilaianInstansi(Request $request)
    {
        if ($request->query('format') === 'pdf') {
            $pdf = Pdf::loadView('templates.form_penilaian_instansi_pdf');
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('Form_Penilaian_Pembimbing_Instansi_SI.pdf');
        }

        $filePath = public_path('doc/form penilaian pembimbing instansi-perusahaan.docx');
        if (file_exists($filePath)) {
            return response()->download($filePath, 'Form_Penilaian_Pembimbing_Instansi_SI.docx');
        }

        $pdf = Pdf::loadView('templates.form_penilaian_instansi_pdf');
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Form_Penilaian_Pembimbing_Instansi_SI.pdf');
    }

    public function downloadFormPenilaianDpl(Request $request)
    {
        $pdf = Pdf::loadView('templates.form_penilaian_dpl_pdf');
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Form_Penilaian_DPL_Magang_SI.pdf');
    }

    public function downloadTemplateIzinMagang()
    {
        $filePath = public_path('doc/Surat_Permohonan_Izin_Magang.docx');
        if (!file_exists($filePath)) {
            $filePath = base_path('Surat_Permohonan_Izin_Magang.docx');
        }

        if (file_exists($filePath)) {
            return response()->download($filePath, 'Surat_Permohonan_Izin_Magang.docx');
        }

        return redirect()->back()->with('error', 'File format Surat Permohonan Izin Magang tidak ditemukan.');
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

        if (!in_array($pengajuan->status, ['active', 'selesai'])) {
            return redirect()->back()->with('error', 'Laporan akhir hanya dapat diunggah pada Fase 5 setelah status magang aktif atau selesai.');
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
