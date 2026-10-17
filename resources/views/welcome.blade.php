<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMAMA - Fakultas Sains dan Teknologi UIN RIL</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans text-gray-800 bg-gray-50 selection:bg-uin selection:text-white">

    <nav class="absolute top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-uin rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-md">
                        S
                    </div>
                    <div>
                        <h1 class="font-bold text-xl text-gray-900 leading-tight">SIMAMA</h1>
                        <p class="text-[10px] uppercase tracking-wider text-gray-500 font-semibold">Fakultas Sains & Teknologi</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-2.5 text-sm font-bold text-white bg-uin rounded-full hover:bg-uin-hover transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        Kembali ke Dasbor
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-uin transition-colors px-4 py-2">Masuk</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-2.5 text-sm font-bold text-white bg-uin rounded-full hover:bg-uin-hover transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        Daftar Sekarang
                    </a>
                    @endif
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('bg-saintek.jpg') }}" alt="Gedung Fakultas Sains dan Teknologi UIN Raden Intan Lampung" class="w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-b from-white/75 via-white/85 to-gray-50"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-uin-light/40 via-transparent to-blue-50/30"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight mb-6">
                Sistem Informasi Manajemen <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-uin to-blue-600">Magang Terpadu</span>
            </h1>
            <p class="mt-4 max-w-2xl text-lg text-gray-600 mx-auto mb-10">
                Platform digital resmi Program Studi Sistem Informasi UIN Raden Intan Lampung. Kelola administrasi magang, logbook kegiatan harian, hingga rekapitulasi penilaian akhir menjadi lebih cepat, efisien, dan terpadu.
            </p>

            @if(!Auth::check())
            <div class="flex justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 text-base font-bold text-white bg-uin rounded-full hover:bg-uin-hover transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Mulai Pengajuan
                </a>
                <a href="#fitur" class="px-8 py-3.5 text-base font-bold text-gray-700 bg-white border border-gray-200 rounded-full hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                    Pelajari Fitur
                </a>
            </div>
            @endif
        </div>
    </div>

    <div id="fitur" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Lebih Cerdas & Terintegrasi</h2>
                <p class="mt-4 text-gray-500">Mendukung penuh kelancaran administrasi lintas program studi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:border-uin/30 hover:shadow-lg transition-all group">
                    <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center text-uin mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Multi-Prodi Ready</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Satu pintu untuk seluruh program studi di Fakultas Saintek. Saat ini dalam tahap Pilot Testing untuk Prodi Sistem Informasi.</p>
                </div>

                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:border-uin/30 hover:shadow-lg transition-all group">
                    <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center text-uin mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Eligibility</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Verifikasi syarat akademik (SKS & IPK) dilakukan otomatis oleh sistem, menghemat waktu admin dan meminimalisir kesalahan.</p>
                </div>

                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:border-uin/30 hover:shadow-lg transition-all group">
                    <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center text-uin mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Penilaian & Evaluasi Terpadu</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Kalkulasi nilai otomatis dari instansi dan DPL dengan lembar penilaian resmi berstandar akademik yang dapat diakses langsung oleh mahasiswa.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-900 border-t border-gray-800 text-gray-400 py-10 text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center items-center space-x-2 mb-4">
                <div class="w-6 h-6 bg-uin rounded flex items-center justify-center text-white font-bold text-xs">S</div>
                <span class="text-gray-100 font-bold tracking-wider">SIMAMA V2</span>
            </div>
            <p class="text-sm">
                &copy; {{ date('Y') }} Fakultas Sains dan Teknologi.<br>
                Universitas Islam Negeri Raden Intan Lampung. Hak Cipta Dilindungi.
            </p>
        </div>
    </footer>
    
</body>

</html>