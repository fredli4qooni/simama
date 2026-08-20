<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MagangApplication;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\StatusPengajuanNotification;

class VerifikasiMagangController extends Controller
{
    public function index()
    {
        $pengajuan = MagangApplication::with(['user.prodi', 'members.user', 'documents.user'])->latest()->get();
        
        return view('admin.verifikasi.index', compact('pengajuan'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'catatan_admin' => 'nullable|string'
        ]);

        $pengajuan = MagangApplication::with(['user', 'members.user'])->findOrFail($id);
        
        $pengajuan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin
        ]);

        // Notifikasi ke ketua
        if ($pengajuan->user) {
            $pengajuan->user->notify(new StatusPengajuanNotification($request->status, $request->catatan_admin));
        }

        // Notifikasi ke seluruh anggota kelompok jika ada
        if ($pengajuan->members) {
            foreach ($pengajuan->members as $member) {
                if ($member->user && $member->user_id !== $pengajuan->user_id) {
                    $member->user->notify(new StatusPengajuanNotification($request->status, $request->catatan_admin));
                }
            }
        }

        $pesan = $request->status == 'approved' ? 'Pengajuan disetujui.' : 'Pengajuan ditolak.';

        return redirect()->back()->with('success', 'Status berhasil diperbarui: ' . $pesan);
    }

    public function cetakSuratPermohonan(string $id)
    {
        $pengajuan = MagangApplication::with(['user.prodi', 'members.user.prodi'])->findOrFail($id);

        if (!in_array($pengajuan->status, ['approved', 'active'])) {
            return redirect()->back()->with('error', 'Surat belum bisa dicetak karena pengajuan belum disetujui.');
        }

        $pdf = Pdf::loadView('admin.verifikasi.surat_permohonan', compact('pengajuan'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Surat_Permohonan_Magang_' . ($pengajuan->user->nim ?? 'Kelompok') . '.pdf');
    }
}