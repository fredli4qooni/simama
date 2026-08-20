<?php

namespace App\Http\Controllers\Dpl;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\MagangApplication;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EvaluasiMagangController extends Controller
{
    public function index(Request $request)
    {
        $dplId = $request->user()->id;

        $mahasiswa = MagangApplication::with(['user', 'members.user', 'evaluation', 'documents'])
            ->whereHas('dplAssignment', function ($query) use ($dplId) {
                $query->where('dpl_user_id', $dplId);
            })->latest()->get();

        return view('dpl.evaluasi.index', compact('mahasiswa'));
    }

    public function store(Request $request, string $id)
    {
        $request->validate([
            'nilai_dpl' => 'required|numeric|min:0|max:100',
            'nilai_instansi' => 'required|numeric|min:0|max:100',
            'komentar' => 'nullable|string',
        ], [
            'nilai_dpl.required' => 'Nilai dari DPL wajib diisi.',
            'nilai_instansi.required' => 'Nilai dari Instansi/Lapangan wajib diisi.',
        ]);

        $nilai_akhir = ($request->nilai_dpl + $request->nilai_instansi) / 2;

        Evaluation::updateOrCreate(
            ['application_id' => $id],
            [
                'dpl_user_id' => $request->user()->id,
                'nilai_dpl' => $request->nilai_dpl,
                'nilai_instansi' => $request->nilai_instansi,
                'nilai_akhir' => $nilai_akhir,
                'komentar' => $request->komentar,
                'published_at' => now(),
            ]
        );

        $application = MagangApplication::findOrFail($id);
        $application->update([
            'status' => 'selesai'
        ]);

        return redirect()->back()->with('success', 'Penilaian berhasil disimpan (Nilai Akhir: ' . number_format($nilai_akhir, 2) . ') dan status magang kini SELESAI.');
    }

    public function cetakPdf(string $id)
    {
        $application = MagangApplication::with(['user.prodi', 'members.user.prodi', 'dplAssignment.dpl', 'evaluation'])->findOrFail($id);

        if (!$application->evaluation) {
            return redirect()->back()->with('error', 'Penilaian belum diinput untuk pengajuan ini.');
        }

        $pdf = Pdf::loadView('dpl.evaluasi.lembar_penilaian', compact('application'));
        $pdf->setPaper('A4', 'portrait');

        $namaMahasiswa = $application->user->name ?? 'Mahasiswa';
        $safeName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $namaMahasiswa);

        return $pdf->stream('Lembar_Penilaian_Magang_' . $safeName . '.pdf');
    }
}