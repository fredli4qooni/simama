<?php

namespace App\Http\Controllers\Dpl;

use App\Http\Controllers\Controller;
use App\Models\MagangApplication;
use App\Models\Logbook;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class PemantauanMagangController extends Controller
{
    public function index(Request $request)
    {
        $dplId = $request->user()->id;

        $mahasiswa = MagangApplication::with(['user', 'members.user', 'evaluation'])
            ->whereHas('dplAssignment', function($query) use ($dplId) {
                $query->where('dpl_user_id', $dplId);
            })->latest()->get();

        return view('dpl.pemantauan.index', compact('mahasiswa'));
    }

    public function show(string $id)
    {
        $pengajuan = MagangApplication::with(['user', 'members.user', 'documents.user'])->findOrFail($id);
        
        Gate::authorize('view', $pengajuan);
        
        $presensi = Attendance::with('user')->where('application_id', $id)->orderBy('date', 'desc')->get();
        $logbooks = Logbook::with('user')->where('application_id', $id)->orderBy('date', 'desc')->get();

        return view('dpl.pemantauan.show', compact('pengajuan', 'presensi', 'logbooks'));
    }

    public function updateFeedback(Request $request, string $id)
    {
        $request->validate([
            'feedback_dpl' => 'required|string'
        ]);

        $logbook = Logbook::with('application.dplAssignment')->findOrFail($id);
        
        Gate::authorize('updateFeedback', $logbook);

        $logbook->update([
            'feedback_dpl' => $request->feedback_dpl
        ]);

        return redirect()->back()->with('success', 'Feedback logbook berhasil disimpan.');
    }

    public function cetakLogbookPdf(string $id, string $userId)
    {
        $pengajuan = MagangApplication::with(['user.prodi', 'members.user.prodi', 'dplAssignment.dpl'])->findOrFail($id);
        Gate::authorize('view', $pengajuan);

        $targetUser = User::with('prodi')->findOrFail($userId);
        $isMember = $pengajuan->user_id == $targetUser->id || $pengajuan->members()->where('user_id', $targetUser->id)->exists();
        if (!$isMember) {
            abort(404, 'Mahasiswa tidak terdaftar pada kelompok magang ini.');
        }

        $logbooks = Logbook::where('application_id', $id)
            ->where('user_id', $userId)
            ->orderBy('date', 'asc')
            ->get();

        $pdf = Pdf::loadView('mahasiswa.kegiatan.logbook_pdf', [
            'pengajuan' => $pengajuan,
            'logbooks' => $logbooks,
            'user' => $targetUser,
        ]);
        $pdf->setPaper('A4', 'portrait');

        $safeNim = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $targetUser->nim ?? 'Mahasiswa');
        return $pdf->stream('Logbook_Magang_' . $safeNim . '.pdf');
    }

    public function cetakPresensiPdf(string $id, string $userId)
    {
        $pengajuan = MagangApplication::with(['user.prodi', 'members.user.prodi', 'dplAssignment.dpl'])->findOrFail($id);
        Gate::authorize('view', $pengajuan);

        $targetUser = User::with('prodi')->findOrFail($userId);
        $isMember = $pengajuan->user_id == $targetUser->id || $pengajuan->members()->where('user_id', $targetUser->id)->exists();
        if (!$isMember) {
            abort(404, 'Mahasiswa tidak terdaftar pada kelompok magang ini.');
        }

        $presensi = Attendance::where('application_id', $id)
            ->where('user_id', $userId)
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $rekap = [
            'total_masuk' => $presensi->where('type', 'masuk')->count(),
            'total_pulang' => $presensi->where('type', 'pulang')->count(),
            'hadir' => $presensi->where('status', 'hadir')->count(),
            'izin' => $presensi->where('status', 'izin')->count(),
            'sakit' => $presensi->where('status', 'sakit')->count(),
        ];

        $pdf = Pdf::loadView('mahasiswa.kegiatan.presensi_pdf', [
            'pengajuan' => $pengajuan,
            'presensi' => $presensi,
            'user' => $targetUser,
            'rekap' => $rekap,
        ]);
        $pdf->setPaper('A4', 'portrait');

        $safeNim = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $targetUser->nim ?? 'Mahasiswa');
        return $pdf->stream('Rekap_Presensi_Magang_' . $safeNim . '.pdf');
    }
}