<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMAMA - Fakultas Sains dan Teknologi UIN Raden Intan Lampung</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans text-gray-800 bg-gray-50 selection:bg-uin selection:text-white">

    <!-- NAVBAR RESPONSIF -->
    <nav class="absolute top-0 w-full z-50 bg-white/85 backdrop-blur-md border-b border-gray-100/80 transition-all">
        <div class="max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-2.5 sm:space-x-3">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-uin rounded-xl flex items-center justify-center text-white font-extrabold text-lg sm:text-xl shadow-md ring-2 ring-uin/20 shrink-0">
                        S
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="font-extrabold text-lg sm:text-xl text-gray-900 tracking-tight leading-none">SIMAMA</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-100 text-uin">FST</span>
                        </div>
                        <p class="text-[9px] sm:text-[10px] uppercase tracking-wider text-gray-500 font-semibold mt-0.5">Sains & Teknologi UIN RIL</p>
                    </div>
                </div>

                <!-- Nav Action Buttons -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3.5 py-2 sm:px-6 sm:py-2.5 text-xs sm:text-sm font-bold text-white bg-uin rounded-full hover:bg-uin-hover transition-all shadow-md hover:shadow-lg transform active:scale-95">
                                <span>Dasbor</span>
                                <svg class="w-3.5 h-3.5 ml-1.5 hidden sm:inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-xs sm:text-sm font-bold text-gray-700 hover:text-uin transition-colors px-2.5 sm:px-4 py-2 rounded-lg hover:bg-gray-100/60">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-3.5 py-2 sm:px-6 sm:py-2.5 text-xs sm:text-sm font-bold text-white bg-uin rounded-full hover:bg-uin-hover transition-all shadow-md hover:shadow-lg transform active:scale-95">
                                    <span>Daftar</span>
                                    <span class="hidden sm:inline">&nbsp;Sekarang</span>
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <div class="relative pt-24 pb-14 sm:pt-36 sm:pb-24 lg:pt-44 lg:pb-32 overflow-hidden">
        <!-- Background Imagery with soft gradients -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('bg-saintek.jpg') }}" alt="Gedung Fakultas Sains dan Teknologi UIN Raden Intan Lampung" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-b from-white/80 via-white/90 to-gray-50"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-uin-light/50 via-transparent to-blue-50/40"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge / Pills -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-uin/10 text-uin text-xs font-semibold mb-5 sm:mb-6 border border-uin/20 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Program Studi Sistem Informasi</span>
            </div>

            <!-- Main Title -->
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight leading-tight sm:leading-tight md:leading-tight mb-4 sm:mb-6">
                Sistem Informasi Manajemen <br class="hidden sm:inline" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-uin via-blue-600 to-indigo-600">Magang Terpadu</span>
            </h1>

            <!-- Subtitle -->
            <p class="mt-3 sm:mt-4 max-w-2xl text-sm sm:text-base md:text-lg text-gray-600 mx-auto mb-8 sm:mb-10 leading-relaxed px-2 sm:px-0">
                Platform digital resmi administrasi magang Fakultas Sains dan Teknologi UIN Raden Intan Lampung. Kelola berkas pengajuan, presensi GPS, logbook harian, hingga evaluasi nilai secara terpadu dan transparan.
            </p>

            <!-- Call to Actions -->
            @if(!Auth::check())
                <div class="flex flex-col sm:flex-row justify-center items-center gap-3 sm:gap-4 max-w-xs sm:max-w-none mx-auto">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-7 py-3.5 text-sm sm:text-base font-bold text-white bg-uin rounded-full hover:bg-uin-hover transition-all shadow-lg hover:shadow-xl transform active:scale-95 hover:-translate-y-0.5">
                        <span>Mulai Pengajuan Magang</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="#fitur" class="w-full sm:w-auto inline-flex justify-center items-center px-7 py-3.5 text-sm sm:text-base font-bold text-gray-700 bg-white/90 border border-gray-200 rounded-full hover:bg-gray-50 hover:border-gray-300 transition-all shadow-xs active:scale-95">
                        Pelajari Fitur
                    </a>
                </div>
            @else
                <div class="flex justify-center max-w-xs sm:max-w-none mx-auto">
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3.5 text-sm sm:text-base font-bold text-white bg-uin rounded-full hover:bg-uin-hover transition-all shadow-lg hover:shadow-xl transform active:scale-95 hover:-translate-y-0.5">
                        <span>Masuk ke Dasbor Saya</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            @endif

            <!-- Quick Stats Bar (Clean & Professional) -->
            <div class="mt-10 sm:mt-16 grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 max-w-4xl mx-auto text-left sm:text-center">
                <div class="bg-white/85 backdrop-blur-xs border border-gray-100 rounded-2xl p-4 shadow-xs hover:border-uin/30 transition-all">
                    <p class="text-xl sm:text-2xl font-black text-uin">100%</p>
                    <p class="text-[11px] sm:text-xs font-semibold text-gray-600 mt-0.5">Digital & Paperless</p>
                </div>
                <div class="bg-white/85 backdrop-blur-xs border border-gray-100 rounded-2xl p-4 shadow-xs hover:border-uin/30 transition-all">
                    <p class="text-xl sm:text-2xl font-black text-uin">GPS Live</p>
                    <p class="text-[11px] sm:text-xs font-semibold text-gray-600 mt-0.5">Presensi Kehadiran</p>
                </div>
                <div class="bg-white/85 backdrop-blur-xs border border-gray-100 rounded-2xl p-4 shadow-xs hover:border-uin/30 transition-all">
                    <p class="text-xl sm:text-2xl font-black text-uin">DPL & Mitra</p>
                    <p class="text-[11px] sm:text-xs font-semibold text-gray-600 mt-0.5">Evaluasi Terpadu</p>
                </div>
                <div class="bg-white/85 backdrop-blur-xs border border-gray-100 rounded-2xl p-4 shadow-xs hover:border-uin/30 transition-all">
                    <p class="text-xl sm:text-2xl font-black text-uin">Resmi FST</p>
                    <p class="text-[11px] sm:text-xs font-semibold text-gray-600 mt-0.5">UIN Raden Intan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FITUR UTAMA -->
    <div id="fitur" class="py-14 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-uin bg-uin/10 px-3 py-1 rounded-full">Keunggulan Sistem</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-3">Lebih Cerdas & Terintegrasi</h2>
                <p class="mt-2 text-xs sm:text-sm text-gray-500 max-w-xl mx-auto">Mendukung penuh kelancaran administrasi magang mahasiswa, pemantauan DPL, dan verifikasi prodi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-8">
                <!-- Card 1 -->
                <div class="bg-gray-50/80 rounded-2xl p-6 sm:p-8 border border-gray-100 hover:border-uin/30 hover:shadow-lg transition-all group">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white rounded-xl shadow-xs flex items-center justify-center text-uin mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Multi-Prodi Ready</h3>
                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">Satu pintu untuk seluruh program studi di Fakultas Saintek. Dilengkapi standarisasi alur dan struktur data akademik yang terintegrasi.</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-gray-50/80 rounded-2xl p-6 sm:p-8 border border-gray-100 hover:border-uin/30 hover:shadow-lg transition-all group">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white rounded-xl shadow-xs flex items-center justify-center text-uin mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Smart Eligibility</h3>
                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">Verifikasi syarat akademik (SKS & IPK) dilakukan otomatis oleh sistem, menghemat waktu admin dan meminimalisir kesalahan.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-gray-50/80 rounded-2xl p-6 sm:p-8 border border-gray-100 hover:border-uin/30 hover:shadow-lg transition-all group">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white rounded-xl shadow-xs flex items-center justify-center text-uin mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Penilaian & Evaluasi Terpadu</h3>
                    <p class="text-gray-600 text-xs sm:text-sm leading-relaxed">Kalkulasi nilai otomatis dari instansi dan DPL dengan lembar penilaian resmi berstandar akademik yang dapat diakses langsung oleh mahasiswa.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ALUR 4 TAHAP MAGANG -->
    <div class="py-14 sm:py-20 bg-gray-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 sm:mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-uin bg-uin/10 px-3 py-1 rounded-full">Alur Pelaksanaan</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-3">4 Tahap Mudah Magang</h2>
                <p class="mt-2 text-xs sm:text-sm text-gray-500 max-w-xl mx-auto">Alur pelaksanaan magang terstandarisasi dari awal pengajuan hingga penerbitan nilai akhir.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Step 1 -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-gray-100 shadow-xs relative">
                    <div class="w-8 h-8 rounded-full bg-uin text-white text-xs font-bold flex items-center justify-center mb-4">
                        1
                    </div>
                    <h4 class="font-bold text-gray-900 text-base mb-1.5">Pengajuan & Berkas</h4>
                    <p class="text-gray-500 text-xs leading-relaxed">Pilih jalur magang prodi atau mandiri, upload berkas persyaratan (KTM, Transkrip, Slip SPP).</p>
                </div>

                <!-- Step 2 -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-gray-100 shadow-xs relative">
                    <div class="w-8 h-8 rounded-full bg-uin text-white text-xs font-bold flex items-center justify-center mb-4">
                        2
                    </div>
                    <h4 class="font-bold text-gray-900 text-base mb-1.5">Surat Izin & DPL</h4>
                    <p class="text-gray-500 text-xs leading-relaxed">Admin memverifikasi berkas, menetapkan Dosen Pembimbing Lapangan (DPL), dan cetak surat pengantar resmi.</p>
                </div>

                <!-- Step 3 -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-gray-100 shadow-xs relative">
                    <div class="w-8 h-8 rounded-full bg-uin text-white text-xs font-bold flex items-center justify-center mb-4">
                        3
                    </div>
                    <h4 class="font-bold text-gray-900 text-base mb-1.5">Presensi GPS & Logbook</h4>
                    <p class="text-gray-500 text-xs leading-relaxed">Mahasiswa melakukan presensi harian berbasis GPS dan mengisi logbook aktivitas dengan pantauan DPL.</p>
                </div>

                <!-- Step 4 -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-gray-100 shadow-xs relative">
                    <div class="w-8 h-8 rounded-full bg-uin text-white text-xs font-bold flex items-center justify-center mb-4">
                        4
                    </div>
                    <h4 class="font-bold text-gray-900 text-base mb-1.5">Evaluasi & Nilai Akhir</h4>
                    <p class="text-gray-500 text-xs leading-relaxed">Penilaian terpadu dari pembimbing instansi dan DPL untuk penerbitan lembar nilai akademik resmi.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="bg-gray-900 border-t border-gray-800 text-gray-400 py-8 sm:py-12 text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center items-center space-x-2 mb-3">
                <div class="w-7 h-7 bg-uin rounded-lg flex items-center justify-center text-white font-extrabold text-xs shadow-sm">S</div>
                <span class="text-white font-bold tracking-wider text-sm sm:text-base">SIMAMA UIN RIL</span>
            </div>
            <p class="text-xs sm:text-sm text-gray-400 max-w-md mx-auto leading-relaxed">
                Fakultas Sains dan Teknologi &bull; Program Studi Sistem Informasi<br>
                Universitas Islam Negeri Raden Intan Lampung
            </p>
            <p class="text-[11px] text-gray-500 mt-4">
                &copy; {{ date('Y') }} SIMAMA. Hak Cipta Dilindungi.
            </p>
        </div>
    </footer>

</body>

</html>