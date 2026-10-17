<?php

namespace App\Http\Controllers\Dpl;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\MagangApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'berkas_nilai_instansi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'berkas_nilai_dpl' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ], [
            'nilai_dpl.required' => 'Nilai dari DPL wajib diisi.',
            'nilai_instansi.required' => 'Nilai dari Instansi/Lapangan wajib diisi.',
            'berkas_nilai_instansi.mimes' => 'Berkas nilai instansi harus berformat PDF, JPG, atau PNG.',
            'berkas_nilai_instansi.max' => 'Ukuran berkas nilai instansi maksimal 10MB.',
            'berkas_nilai_dpl.mimes' => 'Berkas nilai DPL harus berformat PDF, JPG, atau PNG.',
            'berkas_nilai_dpl.max' => 'Ukuran berkas nilai DPL maksimal 10MB.',
        ]);

        $nilai_akhir = ($request->nilai_dpl + $request->nilai_instansi) / 2;

        $data = [
            'dpl_user_id' => $request->user()->id,
            'nilai_dpl' => $request->nilai_dpl,
            'nilai_instansi' => $request->nilai_instansi,
            'nilai_akhir' => $nilai_akhir,
            'komentar' => $request->komentar,
            'published_at' => now(),
        ];

        if ($request->hasFile('berkas_nilai_instansi')) {
            $pathInstansi = $request->file('berkas_nilai_instansi')->store('evaluasi/nilai_instansi', 'public');
            $data['berkas_nilai_instansi'] = $pathInstansi;
        }

        if ($request->hasFile('berkas_nilai_dpl')) {
            $pathDpl = $request->file('berkas_nilai_dpl')->store('evaluasi/nilai_dpl', 'public');
            $data['berkas_nilai_dpl'] = $pathDpl;
        }

        Evaluation::updateOrCreate(
            ['application_id' => $id],
            $data
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

    public function lihatBerkasInstansi(string $id)
    {
        $application = MagangApplication::with('dplAssignment')->findOrFail($id);
        $user = auth()->user();

        $isAuthorized = $user->role === 'admin'
            || $application->user_id == $user->id
            || $application->members()->where('user_id', $user->id)->exists()
            || ($application->dplAssignment && $application->dplAssignment->dpl_user_id == $user->id);

        if (!$isAuthorized) {
            abort(403, 'Akses ditolak.');
        }

        $eval = $application->evaluation;
        if (!$eval || !$eval->berkas_nilai_instansi || !Storage::disk('public')->exists($eval->berkas_nilai_instansi)) {
            abort(404, 'Berkas nilai instansi belum diunggah atau file tidak ditemukan.');
        }

        return Storage::disk('public')->response($eval->berkas_nilai_instansi);
    }

    public function lihatBerkasDpl(string $id)
    {
        $application = MagangApplication::with('dplAssignment')->findOrFail($id);
        $user = auth()->user();

        $isAuthorized = $user->role === 'admin'
            || $application->user_id == $user->id
            || $application->members()->where('user_id', $user->id)->exists()
            || ($application->dplAssignment && $application->dplAssignment->dpl_user_id == $user->id);

        if (!$isAuthorized) {
            abort(403, 'Akses ditolak.');
        }

        $eval = $application->evaluation;
        if (!$eval || !$eval->berkas_nilai_dpl || !Storage::disk('public')->exists($eval->berkas_nilai_dpl)) {
            abort(404, 'Berkas nilai DPL belum diunggah atau file tidak ditemukan.');
        }

        return Storage::disk('public')->response($eval->berkas_nilai_dpl);
    }
}