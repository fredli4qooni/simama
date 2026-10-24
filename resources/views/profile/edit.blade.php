<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight flex items-center gap-2">
                <span class="p-2 bg-uin-light text-uin rounded-lg shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </span>
                Profil Pengguna & Keamanan
            </h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data informasi identitas akun, unggah foto profil{{ $user->role === 'mahasiswa' ? ', data akademik,' : '' }} dan kata sandi Anda.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- INFOBOX JIKA NIM, SKS & IPK BELUM DIISI ATAU BELUM MEMENUHI SYARAT -->
            @if($user->role === 'mahasiswa' && (empty($user->nim) || empty($user->total_sks) || empty($user->ipk) || $user->total_sks < 110 || $user->ipk < 2.75))
                <div class="bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-amber-500/10 border-2 border-amber-400/80 rounded-2xl p-5 shadow-sm flex items-start space-x-4">
                    <div class="p-2.5 bg-amber-500 text-white rounded-xl shadow-sm flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900">Perhatian: Lengkapi NIM & Data Akademik Anda</h4>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                            @if(empty($user->nim) || empty($user->total_sks) || empty($user->ipk))
                                Anda belum melengkapi data <strong>NIM/NPM</strong>, <strong>Total SKS Ditempuh</strong>, atau <strong>IPK Terakhir</strong>. Silakan lengkapi formulir di bawah ini agar memenuhi syarat validasi pendaftaran magang (Minimal: 110 SKS (setara semester 6 ke atas) & IPK 2.75).
                            @else
                                Data SKS Anda saat ini (<strong>{{ $user->total_sks }} SKS</strong>) atau IPK (<strong>{{ number_format($user->ipk, 2) }}</strong>) belum memenuhi syarat minimum pendaftaran magang (Min. 110 SKS & IPK 2.75).
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            <!-- HERO PROFILE CARD WITH AVATAR PREVIEW -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 border-l-4 border-l-uin">
                <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-5">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-2xl object-cover shadow-md border-2 border-white ring-2 ring-uin/30 flex-shrink-0">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-uin to-emerald-700 text-white flex items-center justify-center font-black text-3xl shadow-md border-2 border-white flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="space-y-1 text-center md:text-left">
                        <div class="flex flex-wrap items-center gap-2 justify-center md:justify-start">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full uppercase tracking-wider {{ $user->role === 'mahasiswa' ? 'bg-uin-light text-uin' : ($user->role === 'dpl' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                                {{ $user->role === 'admin' ? 'Admin Prodi SI' : ($user->role === 'dpl' ? 'Dosen Pembimbing' : 'Mahasiswa') }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 font-mono flex items-center gap-2 justify-center md:justify-start">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>{{ $user->email }}</span>
                            </span>
                            @if($user->nim)
                                <span>•</span>
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                    <span>NIM: {{ $user->nim }}</span>
                                </span>
                            @endif
                            @if($user->nip)
                                <span>•</span>
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                    <span>NIP: {{ $user->nip }}</span>
                                </span>
                            @endif
                        </p>
                        <p class="text-xs text-gray-600 font-medium flex items-center gap-1.5 justify-center md:justify-start">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>Program Studi: <strong class="text-gray-900">{{ $user->prodi->nama_prodi ?? 'Sistem Informasi' }}</strong></span>
                        </p>
                    </div>
                </div>

                @if($user->role === 'mahasiswa')
                <div class="flex items-center gap-3 bg-gray-50 p-3.5 rounded-xl border border-gray-200">
                    <div class="px-3.5 py-1.5 bg-white rounded-xl border border-blue-200 text-center shadow-xs">
                        <span class="text-[10px] uppercase font-bold text-gray-400 block">Semester</span>
                        <span class="text-sm font-black text-blue-700">Sem. {{ $user->semester ?? 7 }} ({{ $user->semester_roman }})</span>
                    </div>
                    <div class="px-3.5 py-1.5 bg-white rounded-xl border {{ $user->total_sks >= 110 ? 'border-emerald-200' : 'border-rose-200' }} text-center shadow-xs">
                        <span class="text-[10px] uppercase font-bold text-gray-400 block">Total SKS</span>
                        <span class="text-sm font-black {{ $user->total_sks >= 110 ? 'text-emerald-700' : 'text-rose-600' }}">{{ $user->total_sks ?? 0 }} SKS</span>
                    </div>
                    <div class="px-3.5 py-1.5 bg-white rounded-xl border {{ $user->ipk >= 2.75 ? 'border-emerald-200' : 'border-rose-200' }} text-center shadow-xs">
                        <span class="text-[10px] uppercase font-bold text-gray-400 block">IPK Kumulatif</span>
                        <span class="text-sm font-black {{ $user->ipk >= 2.75 ? 'text-emerald-700' : 'text-rose-600' }}">{{ number_format($user->ipk ?? 0, 2) }}</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- GRID 2 KOLOM: FORM INFORMASI & FORM PASSWORD -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- CARD 1: INFORMASI DATA PENGGUNA & FOTO -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 flex flex-col justify-between">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- CARD 2: KEAMANAN / GANTI PASSWORD -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 flex flex-col justify-between">
                    @include('profile.partials.update-password-form')
                </div>

            </div>

            <!-- CARD 3: HAPUS AKUN -->
            <div class="bg-white rounded-2xl shadow-sm border border-rose-100 p-6 md:p-8">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>
