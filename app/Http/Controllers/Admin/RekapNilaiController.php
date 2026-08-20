<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MagangApplication;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapNilaiController extends Controller
{
    public function index()
    {
        $pengajuan = MagangApplication::with(['user', 'dplAssignment.dpl', 'evaluation'])
            ->whereIn('status', ['active', 'selesai'])
            ->latest()
            ->get();

        return view('admin.rekap.index', compact('pengajuan'));
    }

    public function cetakPdf()
    {
        $pengajuan = MagangApplication::with(['user', 'dplAssignment.dpl', 'evaluation'])
            ->whereHas('evaluation')
            ->get();

        if ($pengajuan->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada data nilai untuk dicetak.');
        }

        $pdf = Pdf::loadView('admin.rekap.cetak_pdf', compact('pengajuan'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Rekapitulasi_Nilai_Magang.pdf');
    }
}