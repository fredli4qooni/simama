<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Logbook;
use App\Models\MagangApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KegiatanMagangController extends Controller
{
    public function presensiIndex(Request $request)
    {
        $user = $request->user();
        $pengajuan = $user->activeMagangApplication();
        
        if (!$pengajuan) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Akses ditolak. Anda belum terdaftar dalam status magang aktif.');
        }

        $presensi = Attendance::where('application_id', $pengajuan->id)
            ->where('user_id', $user->id)
            ->latest('date')
            ->get();

        $hariIniMasuk = Attendance::where('application_id', $pengajuan->id)
            ->where('user_id', $user->id)
            ->where('date', Carbon::today())
            ->where('type', 'masuk')
            ->exists();

        $hariIniPulang = Attendance::where('application_id', $pengajuan->id)
            ->where('user_id', $user->id)
            ->where('date', Carbon::today())
            ->where('type', 'pulang')
            ->exists();

        return view('mahasiswa.kegiatan.presensi', compact('pengajuan', 'presensi', 'hariIniMasuk', 'hariIniPulang'));
    }

    public function presensiStore(Request $request)
    {
        $user = $request->user();
        $pengajuan = $user->activeMagangApplication();

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Anda tidak memiliki magang aktif.');
        }

        $request->validate([
            'type' => 'required|in:masuk,pulang',
            'status' => 'required|in:hadir,izin,sakit',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $sudahPresensi = Attendance::where('application_id', $pengajuan->id)
            ->where('user_id', $user->id)
            ->where('date', Carbon::today())
            ->where('type', $request->type)
            ->exists();
        
        if ($sudahPresensi) {
            return redirect()->back()->with('error', 'Anda sudah mengisi presensi ' . $request->type . ' hari ini.');
        }

        Attendance::create([
            'user_id' => $user->id,
            'application_id' => $pengajuan->id,
            'date' => Carbon::today(),
            'type' => $request->type,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->back()->with('success', 'Presensi ' . $request->type . ' (GPS) hari ini berhasil dicatat.');
    }

    public function logbookIndex(Request $request)
    {
        $user = $request->user();
        $pengajuan = $user->completedOrActiveMagangApplication();
        
        if (!$pengajuan) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Akses ditolak. Anda belum memiliki data magang aktif atau selesai.');
        }

        $logbooks = Logbook::where('application_id', $pengajuan->id)
            ->where('user_id', $user->id)
            ->latest('date')
            ->get();

        return view('mahasiswa.kegiatan.logbook', compact('pengajuan', 'logbooks'));
    }

    public function logbookStore(Request $request)
    {
        $user = $request->user();
        $pengajuan = $user->completedOrActiveMagangApplication();

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Anda tidak memiliki magang aktif.');
        }

        if ($pengajuan->status === 'selesai') {
            return redirect()->back()->with('error', 'Kegiatan magang telah berstatus selesai. Periode pengisian logbook telah ditutup.');
        }

        if (!in_array($pengajuan->status, ['active', 'approved'])) {
            return redirect()->back()->with('error', 'Status magang Anda saat ini belum aktif.');
        }

        $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'kegiatan' => 'required|string',
            'output' => 'required|string'
        ]);

        $sudahIsi = Logbook::where('application_id', $pengajuan->id)
            ->where('user_id', $user->id)
            ->where('date', $request->date)
            ->exists();
        
        if ($sudahIsi) {
            return redirect()->back()->with('error', 'Logbook untuk tanggal tersebut sudah pernah diisi.');
        }

        Logbook::create([
            'user_id' => $user->id,
            'application_id' => $pengajuan->id,
            'date' => $request->date,
            'kegiatan' => $request->kegiatan,
            'output' => $request->output
        ]);

        return redirect()->back()->with('success', 'Logbook harian berhasil ditambahkan.');
    }

    public function cetakLogbookPdf(Request $request, ?string $id = null)
    {
        $user = $request->user();

        if ($id) {
            $pengajuan = MagangApplication::with(['user.prodi', 'members.user.prodi', 'dplAssignment.dpl'])->findOrFail($id);
            $isAuthorized = $pengajuan->user_id == $user->id || $pengajuan->members()->where('user_id', $user->id)->exists();
            if (!$isAuthorized) {
                abort(403, 'Akses ditolak. Anda tidak terdaftar pada pengajuan magang ini.');
            }
        } else {
            $pengajuan = $user->activeMagangApplication()
                ?? $user->magangApplications()->whereIn('status', ['active', 'selesai', 'approved'])->latest()->first()
                ?? MagangApplication::whereHas('members', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->whereIn('status', ['active', 'selesai', 'approved'])->latest()->first();

            if (!$pengajuan) {
                return redirect()->route('mahasiswa.dashboard')->with('error', 'Tidak ditemukan data kegiatan magang yang aktif atau selesai.');
            }
        }

        if (!in_array($pengajuan->status, ['active', 'selesai', 'approved'])) {
            return redirect()->back()->with('error', 'Cetak logbook belum dapat dilakukan untuk status pengajuan ini.');
        }

        $pengajuan->loadMissing(['user.prodi', 'members.user.prodi', 'dplAssignment.dpl']);

        $logbooks = Logbook::where('application_id', $pengajuan->id)
            ->where('user_id', $user->id)
            ->orderBy('date', 'asc')
            ->get();

        $pdf = Pdf::loadView('mahasiswa.kegiatan.logbook_pdf', compact('pengajuan', 'logbooks', 'user'));
        $pdf->setPaper('A4', 'portrait');

        $safeNim = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $user->nim ?? 'Mahasiswa');
        return $pdf->stream('Logbook_Magang_' . $safeNim . '.pdf');
    }

    public function downloadTemplateLogbookDocx()
    {
        $filePath = public_path('doc/Log_Book_Kegiatan_Magang.docx');
        if (!file_exists($filePath)) {
            $filePath = base_path('Log_Book_Kegiatan_Magang.docx');
        }

        if (file_exists($filePath)) {
            return response()->download($filePath, 'Log_Book_Kegiatan_Magang.docx');
        }

        return redirect()->back()->with('error', 'File format Logbook tidak ditemukan.');
    }
}