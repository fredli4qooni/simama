<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIMAMA') }} - UIN Raden Intan Lampung</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-slate-50 selection:bg-uin selection:text-white min-h-screen">

    <div class="flex min-h-screen">

        <!-- SISI KIRI: HERO BRANDING (DESKTOP) -->
        <div class="hidden lg:flex lg:w-7/12 relative items-center justify-center overflow-hidden bg-uin">
            <img src="{{ asset('bg-saintek.jpg') }}" alt="Fakultas Sains dan Teknologi UIN Raden Intan Lampung" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-uin/90 via-uin-hover/85 to-blue-950/90 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-uin/30"></div>

            <div class="relative z-10 px-12 xl:px-16 text-white max-w-2xl">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 px-3 py-1.5 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-xs text-xs font-semibold text-blue-100 mb-8 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali ke Beranda Utama</span>
                </a>

                <h1 class="text-4xl xl:text-5xl font-extrabold mb-6 leading-tight tracking-tight">
                    Sistem Informasi <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 via-sky-100 to-white">Manajemen Magang</span>
                </h1>

                <p class="text-base text-blue-100 mb-8 border-l-4 border-emerald-400 pl-4 leading-relaxed">
                    Program Studi Sistem Informasi &bull; Fakultas Sains dan Teknologi<br>
                    Universitas Islam Negeri (UIN) Raden Intan Lampung
                </p>

                <div class="grid grid-cols-3 gap-4 text-xs font-bold text-blue-100/90 pt-4 border-t border-white/15">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>100% Paperless</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>GPS Presensi Live</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Evaluasi Terpadu</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- SISI KANAN: FORM AUTH DALAM CARD (RESPONSIF MOBILE & DESKTOP) -->
        <div class="flex flex-col justify-between w-full lg:w-5/12 px-3.5 sm:px-6 md:px-10 lg:px-8 xl:px-12 py-5 sm:py-8 bg-slate-50 lg:bg-slate-50/70 border-l border-gray-100/80 z-20 overflow-y-auto">
            
            <!-- NAVIGASI KEMBALI KE BERANDA (MOBILE & DESKTOP) -->
            <div class="w-full max-w-md mx-auto flex items-center justify-between pb-3 sm:pb-4">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-uin transition-colors py-1.5 px-2.5 rounded-lg hover:bg-white shadow-2xs group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Beranda</span>
                </a>
                <span class="text-[10px] font-bold uppercase tracking-wider text-uin bg-uin-light px-2.5 py-1 rounded-full">SIMAMA FST</span>
            </div>

            <!-- CONTAINER KARTU FORM AUTH -->
            <div class="w-full max-w-md mx-auto my-auto py-2">
                <!-- ELEVATED WHITE CARD CONTAINER -->
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-5 sm:p-7 md:p-8 relative">
                    {{ $slot }}
                </div>
            </div>

            <!-- FOOTER HAK CIPTA -->
            <div class="w-full max-w-md mx-auto pt-4 sm:pt-6 text-center text-[11px] text-gray-400">
                &copy; {{ date('Y') }} Prodi Sistem Informasi &bull; UIN Raden Intan Lampung
            </div>
        </div>

    </div>

</body>

</html>