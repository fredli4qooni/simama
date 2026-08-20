<?php

namespace App\Http\Controllers\Dpl;

use App\Http\Controllers\Controller;
use App\Models\MagangApplication;
use App\Models\Logbook;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
}