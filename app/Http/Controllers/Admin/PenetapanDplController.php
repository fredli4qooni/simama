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

        $query = MagangApplication::with(['user.prodi', 'members.user', 'dplAssignment.dpl', 'documents'])
            ->whereIn('status', ['approved', 'active']);

        if ($user->prodi_id) {
            $query->where(function ($q) use ($user) {
                $q->where('prodi_id', $user->prodi_id)
                  ->orWhereNull('prodi_id')
                  ->orWhereHas('user', function ($uq) use ($user) {
                      $uq->where('prodi_id', $user->prodi_id)
                         ->orWhereNull('prodi_id');
                  });
            });
        }

        $pengajuan = $query->latest()->get();

        $dosenQuery = User::where('role', 'dpl')
            ->where('is_active', true);

        if ($user->prodi_id) {
            $dosenQuery->where(function ($dq) use ($user) {
                $dq->where('prodi_id', $user->prodi_id)
                   ->orWhereNull('prodi_id');
            });
        }

        $dosen = $dosenQuery->get();

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

        $adminUser = $request->user();
        $updateData = ['status' => 'active'];
        if (!$pengajuan->prodi_id && $adminUser && $adminUser->prodi_id) {
            $updateData['prodi_id'] = $adminUser->prodi_id;
        }
        $pengajuan->update($updateData);

        if ($pengajuan->user && !$pengajuan->user->prodi_id && $adminUser && $adminUser->prodi_id) {
            $pengajuan->user->update(['prodi_id' => $adminUser->prodi_id]);
        }

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
