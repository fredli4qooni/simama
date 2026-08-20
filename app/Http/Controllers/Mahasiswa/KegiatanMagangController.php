<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Logbook;
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
        $pengajuan = $user->activeMagangApplication();
        
        if (!$pengajuan) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki status magang aktif.');
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
        $pengajuan = $user->activeMagangApplication();

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Anda tidak memiliki magang aktif.');
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
}