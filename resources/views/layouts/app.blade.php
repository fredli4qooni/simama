<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIMAMA') }} - UIN Raden Intan Lampung</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-800 bg-gray-50">

    <div class="flex h-screen overflow-hidden bg-gray-50">

        <!-- SIDEBAR -->
        <aside class="flex flex-col w-64 text-white transition-all duration-300 shadow-lg bg-uin flex-shrink-0">
            <div class="flex flex-col items-center justify-center h-20 border-b border-uin-hover">
                <h1 class="text-2xl font-bold tracking-wider">SIMAMA</h1>
                <span class="text-xs text-blue-200">Sistem Informasi Magang</span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 space-x-3 text-sm font-medium rounded-md {{ request()->routeIs('*.dashboard', 'dashboard') ? 'bg-uin-hover text-white' : 'text-blue-100 hover:bg-uin-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                @if(Auth::user()->role === 'admin')
                <p class="px-4 pt-4 pb-2 text-xs font-semibold text-blue-300 uppercase tracking-wider">Manajemen Magang</p>
                <a href="{{ route('admin.verifikasi.index') }}" class="flex items-center px-4 py-3 space-x-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.verifikasi.*') ? 'bg-uin-hover text-white' : 'text-blue-100 hover:bg-uin-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Verifikasi Berkas</span>
                </a>
                <a href="{{ route('admin.penetapan_dpl.index') }}" class="flex items-center px-4 py-3 space-x-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.penetapan_dpl.*') ? 'bg-uin-hover text-white' : 'text-blue-100 hover:bg-uin-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Penetapan DPL</span>
                </a>
                <a href="{{ route('admin.rekap.index') }}" class="flex items-center px-4 py-3 space-x-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.rekap.*') ? 'bg-uin-hover text-white' : 'text-blue-100 hover:bg-uin-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Rekap Nilai</span>
                </a>
                @endif

                @if(Auth::user()->role === 'admin')
                <p class="px-4 pt-4 pb-2 text-xs font-semibold text-blue-300 uppercase tracking-wider">Sistem</p>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 space-x-3 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-uin-hover text-white' : 'text-blue-100 hover:bg-uin-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Manajemen Pengguna</span>
                </a>
                @endif

                @if(Auth::user()->role === 'dpl')
                <p class="px-4 pt-4 pb-2 text-xs font-semibold text-blue-300 uppercase tracking-wider">Pembimbingan</p>
                <a href="{{ route('dpl.pemantauan.index') }}" class="flex items-center px-4 py-3 space-x-3 text-sm font-medium rounded-md {{ request()->routeIs('dpl.pemantauan.*') ? 'bg-uin-hover text-white' : 'text-blue-100 hover:bg-uin-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span>Daftar Bimbingan</span>
                </a>
                <a href="{{ route('dpl.evaluasi.index') }}" class="flex items-center px-4 py-3 space-x-3 text-sm font-medium rounded-md {{ request()->routeIs('dpl.evaluasi.*') ? 'bg-uin-hover text-white' : 'text-blue-100 hover:bg-uin-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Penilaian Akhir</span>
                </a>
                @endif

                @if(Auth::user()->role === 'mahasiswa')
                <p class="px-4 pt-4 pb-2 text-xs font-semibold text-blue-300 uppercase tracking-wider">Aktivitas Saya</p>
                <a href="{{ route('mahasiswa.pengajuan.index') }}" class="flex items-center px-4 py-3 space-x-3 text-sm font-medium rounded-md {{ request()->routeIs('mahasiswa.pengajuan.*') ? 'bg-uin-hover text-white' : 'text-blue-100 hover:bg-uin-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Pengajuan Magang</span>
                </a>
                <a href="{{ route('mahasiswa.presensi.index') }}" class="flex items-center px-4 py-3 space-x-3 text-sm font-medium rounded-md {{ request()->routeIs('mahasiswa.presensi.*') ? 'bg-uin-hover text-white' : 'text-blue-100 hover:bg-uin-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Presensi Harian</span>
                </a>
                <a href="{{ route('mahasiswa.logbook.index') }}" class="flex items-center px-4 py-3 space-x-3 text-sm font-medium rounded-md {{ request()->routeIs('mahasiswa.logbook.*') ? 'bg-uin-hover text-white' : 'text-blue-100 hover:bg-uin-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span>Logbook Kegiatan</span>
                </a>
                @endif

                <!-- MENU PENGATURAN / PROFIL SAYA (UNTUK SEMUA ROLE) -->
                <p class="px-4 pt-4 pb-2 text-xs font-semibold text-blue-300 uppercase tracking-wider">Pengaturan Akun</p>
                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 space-x-3 text-sm font-medium rounded-md {{ request()->routeIs('profile.*') ? 'bg-uin-hover text-white' : 'text-blue-100 hover:bg-uin-hover hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Profil Saya</span>
                </a>
            </nav>

            <!-- SIDEBAR FOOTER (LOGOUT BUTTON) -->
            <div class="p-3.5 border-t border-uin-hover/80">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 space-x-2 text-xs font-bold rounded-xl bg-white hover:bg-rose-50 text-rose-600 hover:text-rose-700 shadow-sm hover:shadow transition-all duration-150 group">
                        <svg class="w-4 h-4 text-rose-600 group-hover:text-rose-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Keluar (Log Out)</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- CONTENT WRAPPER -->
        <div class="flex flex-col flex-1 w-full overflow-hidden">

            <!-- HEADER BAR -->
            <header class="flex items-center justify-between px-6 py-3.5 bg-white border-b border-gray-200/80 shadow-xs z-10">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none md:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div>
                        {{ $header ?? '' }}
                    </div>
                </div>

                <!-- RIGHT HEADER CONTROLS -->
                <div class="flex items-center space-x-4">
                    <!-- USER PILL DROPDOWN -->
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center space-x-3 px-2 py-1.5 rounded-full border border-gray-200 bg-gray-50/70 hover:bg-blue-50/50 hover:border-blue-300 text-sm font-medium text-gray-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-uin/30 group">
                                @if(Auth::user()->avatar_url)
                                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover shadow-sm ring-2 ring-white flex-shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-uin to-blue-800 text-white flex items-center justify-center font-black text-xs shadow-sm ring-2 ring-white flex-shrink-0">
                                        {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                                    </div>
                                @endif
                                
                                <div class="text-left hidden sm:block">
                                    <div class="font-bold text-xs text-gray-800 leading-tight max-w-[130px] truncate group-hover:text-uin transition-colors">
                                        {{ Auth::user()->name ?? 'Pengguna' }}
                                    </div>
                                    <div class="text-[10px] font-semibold text-uin leading-tight flex items-center gap-1 mt-0.5 capitalize">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        @if(Auth::user()->role === 'admin') Admin Prodi SI
                                        @elseif(Auth::user()->role === 'dpl') Dosen Pembimbing
                                        @else Mahasiswa SI
                                        @endif
                                    </div>
                                </div>

                                <div class="text-gray-400 group-hover:text-gray-600 transition-colors ml-0.5">
                                    <svg class="w-4 h-4 fill-current transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Dropdown User Info Header -->
                            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50 rounded-t-xl">
                                <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-gray-500 truncate">{{ Auth::user()->email }} ({{ Auth::user()->role === 'admin' ? 'Admin Prodi SI' : (Auth::user()->role === 'dpl' ? 'DPL' : 'Mahasiswa') }})</p>
                            </div>

                            <div class="py-1">
                                <x-dropdown-link :href="route('profile.edit')" class="flex items-center text-xs font-medium text-gray-700 hover:bg-uin-light hover:text-uin">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ __('Profil Saya') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center text-xs font-medium text-rose-600 hover:bg-rose-50 hover:text-rose-700">
                                        <svg class="w-4 h-4 mr-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        {{ __('Keluar (Log Out)') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                {{ $slot }}
            </main>

        </div>
    </div>
    <div class="fixed top-6 right-6 z-[100] flex flex-col gap-3 pointer-events-none">
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-8"
            class="bg-blue-600 text-white px-5 py-3 rounded-xl shadow-xl flex items-center gap-3 pointer-events-auto border border-blue-500">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm font-semibold tracking-wide">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-8"
            class="bg-red-600 text-white px-5 py-3 rounded-xl shadow-xl flex items-center gap-3 pointer-events-auto border border-red-500">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm font-semibold tracking-wide">{{ session('error') }}</span>
        </div>
        @endif
    </div>
</body>

</html>