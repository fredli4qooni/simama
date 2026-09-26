<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MagangApplication;
use App\Models\DplAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\PenetapanDplNotification;

class PenetapanDplController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $user = $request->user();

        $pengajuan = MagangApplication::with(['user', 'members.user', 'dplAssignment.dpl'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('prodi_id', $user->prodi_id);
            })
            ->whereIn('status', ['approved', 'active'])
            ->latest()
            ->get();

        $dosen = User::where('role', 'dpl')
            ->where('prodi_id', $user->prodi_id)
            ->where('is_active', true)
            ->get();

        return view('admin.penetapan_dpl.index', compact('pengajuan', 'dosen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:magang_applications,id',
            'dpl_user_id' => 'required|exists:users,id',
        ]);

        $pengajuan = MagangApplication::with(['user', 'members.user'])->findOrFail($request->application_id);

        $hasSuratPenerimaan = $pengajuan->documents()->whereIn('type', ['SURAT_PENERIMAAN', 'BUKTI_MAGANG_MANDIRI'])->exists();

        if (!$hasSuratPenerimaan) {
            return redirect()->back()->with('error', 'DPL belum bisa ditetapkan karena mahasiswa/kelompok belum mengunggah Surat Penerimaan / Bukti Magang Mandiri.');
        }

        DplAssignment::updateOrCreate(
            ['application_id' => $request->application_id],
            ['dpl_user_id' => $request->dpl_user_id]
        );

        $dpl = User::find($request->dpl_user_id);
        if ($dpl) {
            $dpl->notify(new PenetapanDplNotification($pengajuan));
        }

        $pengajuan->update(['status' => 'active']);

        return redirect()->back()->with('success', 'DPL berhasil ditetapkan untuk kelompok mahasiswa dan status magang sekarang Aktif.');
    }

    public function cetakSuratTugas(string $id)
    {
        $assignment = \App\Models\DplAssignment::with(['application.user.prodi', 'application.members.user.prodi', 'dpl'])->findOrFail($id);

        $namaMahasiswa = $assignment->application->user->name ?? 'Mahasiswa';
        $safeName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $namaMahasiswa);

        $filename = 'Surat_Tugas_DPL_' . $safeName . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.penetapan_dpl.surat', compact('assignment'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream($filename);
    }
}
