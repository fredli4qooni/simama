<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\PengajuanMagangController;
use App\Http\Controllers\Admin\VerifikasiMagangController;
use App\Http\Controllers\Admin\PenetapanDplController;
use App\Http\Controllers\Mahasiswa\KegiatanMagangController;
use App\Http\Controllers\Dpl\PemantauanMagangController;
use App\Http\Controllers\Dpl\EvaluasiMagangController;
use App\Http\Controllers\Admin\RekapNilaiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ConsultationController;

Route::get('/', function () {
    return view('welcome');
});

/* ==========================================
   RUTE HUB DASHBOARD (POLISI LALU LINTAS)
========================================== */
Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    $role = $request->user()->role;
    
    return match ($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'dpl' => redirect()->route('dpl.dashboard'),
        'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
        default => redirect('/'),
    };
})->middleware('auth')->name('dashboard');

/* ==========================================
   MODUL ADMIN PROGRAM STUDI SISTEM INFORMASI
========================================== */
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); 
    })->name('dashboard');
    
    // Verifikasi Berkas Magang
    Route::get('/verifikasi', [VerifikasiMagangController::class, 'index'])->name('verifikasi.index');
    Route::patch('/verifikasi/{id}', [VerifikasiMagangController::class, 'update'])->name('verifikasi.update');
    Route::get('/verifikasi/{id}/surat', [VerifikasiMagangController::class, 'cetakSuratPermohonan'])->name('verifikasi.surat');
    
    // Penetapan DPL & Surat Tugas
    Route::get('/penetapan-dpl', [PenetapanDplController::class, 'index'])->name('penetapan_dpl.index');
    Route::post('/penetapan-dpl', [PenetapanDplController::class, 'store'])->name('penetapan_dpl.store');
    Route::get('/penetapan-dpl/{id}/surat', [PenetapanDplController::class, 'cetakSuratTugas'])->name('penetapan_dpl.surat');

    // Rekapitulasi Nilai Akhir Magang
    Route::get('/rekap-nilai', [RekapNilaiController::class, 'index'])->name('rekap.index');
    Route::get('/rekap-nilai/cetak', [RekapNilaiController::class, 'cetakPdf'])->name('rekap.cetak');

    // Manajemen Pengguna (DPL & Mahasiswa SI)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

/* ==========================================
   MODUL DOSEN PEMBIMBING LAPANGAN (DPL)
========================================== */
Route::middleware(['auth', 'role:dpl'])->prefix('dpl')->name('dpl.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/pemantauan', [PemantauanMagangController::class, 'index'])->name('pemantauan.index');
    Route::get('/pemantauan/{id}', [PemantauanMagangController::class, 'show'])->name('pemantauan.show');
    Route::patch('/pemantauan/logbook/{id}', [PemantauanMagangController::class, 'updateFeedback'])->name('pemantauan.feedback');

    Route::get('/evaluasi', [EvaluasiMagangController::class, 'index'])->name('evaluasi.index');
    Route::post('/evaluasi/{id}', [EvaluasiMagangController::class, 'store'])->name('evaluasi.store');
    Route::get('/evaluasi/{id}/cetak', [EvaluasiMagangController::class, 'cetakPdf'])->name('evaluasi.cetak');
});

/* ==========================================
   MODUL MAHASISWA SISTEM INFORMASI
========================================== */
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/pengajuan', [PengajuanMagangController::class, 'index'])->name('pengajuan.index');
    Route::post('/pengajuan', [PengajuanMagangController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan/{id}/surat-pengantar', [PengajuanMagangController::class, 'cetakSuratPengantar'])->name('pengajuan.surat_pengantar');
    Route::post('/pengajuan/{id}/surat-balasan', [PengajuanMagangController::class, 'uploadSuratBalasan'])->name('pengajuan.surat_balasan');
    Route::get('/template-laporan', [PengajuanMagangController::class, 'downloadTemplateLaporan'])->name('laporan.template');
    Route::post('/pengajuan/{id}/laporan-akhir', [PengajuanMagangController::class, 'uploadLaporanAkhir'])->name('pengajuan.laporan_akhir');

    Route::get('/presensi', [KegiatanMagangController::class, 'presensiIndex'])->name('presensi.index');
    Route::post('/presensi', [KegiatanMagangController::class, 'presensiStore'])->name('presensi.store');
    
    Route::get('/logbook', [KegiatanMagangController::class, 'logbookIndex'])->name('logbook.index');
    Route::post('/logbook', [KegiatanMagangController::class, 'logbookStore'])->name('logbook.store');
});

/* ==========================================
   MODUL UMUM (PROFIL & KONSULTASI)
========================================== */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/konsultasi/{applicationId}', [ConsultationController::class, 'store'])->name('konsultasi.store');
});

require __DIR__.'/auth.php';