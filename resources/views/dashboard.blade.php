<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard 
            @if(auth()->user()->role === 'admin') Admin Prodi Sistem Informasi
            @elseif(auth()->user()->role === 'dpl') Dosen Pembimbing (DPL)
            @else Mahasiswa Sistem Informasi
            @endif
        </h2>
    </x-slot>

    <div class="py-3.5 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8">

            {{-- ==================== DASHBOARD MAHASISWA (BENTO GRID) ==================== --}}
            @if(auth()->user()->role === 'mahasiswa')
                @php
                    $mhsPengajuan = auth()->user()->latestMagangApplication();
                    $mhsDpl = $mhsPengajuan->dplAssignment->dpl ?? null;
                    $hasProfileWarning = empty(auth()->user()->nim) || empty(auth()->user()->total_sks) || empty(auth()->user()->ipk) || auth()->user()->total_sks < 110 || auth()->user()->ipk < 2.75;
                    $hasNilaiAkhir = $mhsPengajuan && $mhsPengajuan->evaluation;
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3.5 sm:gap-5">

                    {{-- Slim Alert jika keduanya aktif: Nilai Ada DAN Profil belum lengkap --}}
                    @if($hasProfileWarning && $hasNilaiAkhir)
                        <div class="col-span-1 md:col-span-2 lg:col-span-12 bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-amber-500/10 border border-amber-300 rounded-2xl p-3.5 sm:p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xs">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-amber-500 text-white rounded-xl shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900">Perhatian: Data Profil Belum Lengkap</h4>
                                    <p class="text-[11px] text-gray-600">Lengkapi NPM, total SKS (min. 110 SKS), dan IPK (min. 2.75) pada akun Anda.</p>
                                </div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-3.5 py-1.5 bg-amber-600 hover:bg-amber-700 active:scale-98 text-white text-xs font-bold rounded-xl shadow-xs transition whitespace-nowrap">
                                Lengkapi Profil Sekarang ↗
                            </a>
                        </div>
                    @endif

                    {{-- 1. BENTO HERO: Welcome Card --}}
                    <div class="col-span-1 md:col-span-2 lg:col-span-8 bg-gradient-to-br from-blue-700 via-uin to-uin-hover text-white rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-sm border border-blue-600/30 relative overflow-hidden flex flex-col justify-between min-h-[190px]">
                        {{-- Background glows --}}
                        <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="absolute left-1/3 -top-10 w-52 h-52 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10 space-y-2">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-white/20 backdrop-blur-sm text-[10px] sm:text-[11px] font-bold tracking-wide uppercase">
                                    Program Studi Sistem Informasi
                                </span>
                                <span class="text-blue-100 text-[11px] font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}
                                </span>
                            </div>

                            <div class="pt-1">
                                <h3 class="text-xl sm:text-2xl md:text-3xl font-black tracking-tight break-words">
                                    Halo, {{ auth()->user()->name }}!
                                </h3>
                                <p class="text-blue-100 text-xs sm:text-sm mt-1 max-w-xl leading-relaxed">
                                    Sistem Informasi Manajemen Kerja Praktik & Magang Mahasiswa (SIMAMA) UIN Raden Intan Lampung.
                                </p>
                            </div>
                        </div>

                        {{-- Quick student badges --}}
                        <div class="relative z-10 pt-4 flex flex-wrap items-center gap-2">
                            <div class="bg-white/15 backdrop-blur-xs px-2.5 py-1 rounded-lg text-xs font-mono font-medium text-white">
                                NPM: {{ auth()->user()->nim ?? '-' }}
                            </div>
                            <div class="bg-white/15 backdrop-blur-xs px-2.5 py-1 rounded-lg text-xs font-medium text-white">
                                Semester {{ auth()->user()->semester_roman ?? 'VII' }}
                            </div>
                            @if(!empty(auth()->user()->total_sks) && !empty(auth()->user()->ipk))
                                <div class="bg-white/15 backdrop-blur-xs px-2.5 py-1 rounded-lg text-xs font-medium text-white">
                                    {{ auth()->user()->total_sks }} SKS • IPK {{ number_format(auth()->user()->ipk, 2) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. BENTO TILE: Profil DPL --}}
                    <div class="col-span-1 md:col-span-2 lg:col-span-4 bg-white rounded-2xl sm:rounded-3xl p-5 border border-gray-100 shadow-xs flex flex-col justify-between min-h-[190px]">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-[10px] font-black uppercase tracking-wider text-gray-500 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Dosen Pembimbing (DPL)
                                </span>
                                @if($mhsDpl)
                                    <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full bg-emerald-100 text-emerald-800">Ditetapkan</span>
                                @else
                                    <span class="px-2 py-0.5 text-[9px] font-bold uppercase rounded-full bg-amber-100 text-amber-800">Menunggu</span>
                                @endif
                            </div>

                            @if($mhsDpl)
                                <div class="flex items-start space-x-3 pt-1">
                                    @if($mhsDpl->avatar_url)
                                        <img src="{{ $mhsDpl->avatar_url }}" alt="{{ $mhsDpl->name }}" class="w-11 h-11 rounded-full object-cover ring-2 ring-blue-500/30 shadow-xs shrink-0">
                                    @else
                                        <div class="w-11 h-11 rounded-full bg-uin text-white font-bold flex items-center justify-center text-sm shadow-xs shrink-0" style="background-color: #1d4ed8;">
                                            {{ strtoupper(substr($mhsDpl->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1">
                                            <p class="font-bold text-gray-900 text-xs truncate" title="{{ $mhsDpl->name }}">{{ $mhsDpl->name }}</p>
                                            <span class="text-blue-600 shrink-0" title="DPL Resmi">
                                                <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            </span>
                                        </div>
                                        <p class="text-gray-600 text-[11px] font-mono truncate mt-0.5">
                                            {{ !empty($mhsDpl->nip) ? 'NIP. ' . $mhsDpl->nip : (!empty($mhsDpl->nim) ? 'NIDN. ' . $mhsDpl->nim : 'Dosen Pembimbing') }}
                                        </p>
                                        <a href="mailto:{{ $mhsDpl->email }}" class="text-blue-600 hover:text-blue-700 text-[10px] truncate flex items-center gap-1 mt-1 font-medium group">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            <span class="truncate group-hover:underline">{{ $mhsDpl->email }}</span>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center space-x-3 py-1 text-gray-500">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-xs">Belum Ditetapkan</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5 leading-tight">Admin prodi akan menetapkan DPL setelah berkas disetujui.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-gray-100 text-[10px] text-gray-400 flex items-center justify-between">
                            <span>Status Bimbingan</span>
                            <span class="font-medium text-gray-600">{{ $mhsDpl ? 'Terkoneksi Otomatis' : 'Menunggu Berkas' }}</span>
                        </div>
                    </div>

                    {{-- 3. BENTO TILE: Status Tempat Magang --}}
                    <div class="col-span-1 md:col-span-1 lg:col-span-7 bg-white rounded-2xl sm:rounded-3xl p-5 border border-gray-100 shadow-xs flex flex-col justify-between min-h-[180px]">
                        <div>
                            @if($mhsPengajuan)
                                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-2.5">
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $mhsPengajuan->jenis_magang === 'mandiri' ? 'bg-purple-100 text-purple-800' : 'bg-uin-light text-uin' }}">
                                        {{ $mhsPengajuan->jenis_magang === 'mandiri' ? 'Magang Mandiri' : 'Magang Program Studi' }}
                                    </span>
                                    @if($mhsPengajuan->status == 'pending')
                                        <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 text-amber-800 rounded-full text-[10px] font-bold border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span> Verifikasi Berkas
                                        </span>
                                    @elseif($mhsPengajuan->status == 'approved')
                                        <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-800 rounded-full text-[10px] font-bold border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5 animate-pulse"></span> Disetujui (Plotting DPL)
                                        </span>
                                    @elseif($mhsPengajuan->status == 'active')
                                        <span class="inline-flex items-center px-2 py-0.5 bg-emerald-50 text-emerald-800 rounded-full text-[10px] font-bold border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-ping"></span> Magang Berjalan Aktif
                                        </span>
                                    @elseif($mhsPengajuan->status == 'selesai')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-purple-50 text-purple-800 rounded-full text-[10px] font-bold border border-purple-200">
                                            <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span>Selesai & Terverifikasi</span>
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-lg sm:text-xl font-black text-gray-900 tracking-tight break-words">{{ $mhsPengajuan->instansi }}</h3>
                                <p class="text-xs text-gray-500 flex items-start mt-1 break-words">
                                    <svg class="w-3.5 h-3.5 mr-1 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    <span class="line-clamp-2">{{ $mhsPengajuan->alamat ?? $mhsPengajuan->alamat_instansi ?? 'Alamat instansi mitra' }}</span>
                                </p>
                            @else
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full bg-gray-100 text-gray-700">Status Magang</span>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200">Belum Mengajukan</span>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 tracking-tight">Belum Ada Pengajuan Magang Aktif</h3>
                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                    Anda belum mendaftarkan permohonan magang untuk periode ini. Silakan mulai pendaftaran magang mandiri atau program studi.
                                </p>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                            @if($mhsPengajuan)
                                <span class="text-gray-500 font-mono text-[11px] flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>{{ \Carbon\Carbon::parse($mhsPengajuan->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($mhsPengajuan->end_date)->format('d M Y') }}</span>
                                </span>
                                <a href="{{ route('mahasiswa.pengajuan.index') }}" class="text-uin hover:text-uin-hover font-bold text-xs inline-flex items-center">
                                    Detail Pengajuan →
                                </a>
                            @else
                                <span class="text-gray-400 text-[11px]">Syarat: Min. 110 SKS & IPK 2.75</span>
                                <a href="{{ route('mahasiswa.pengajuan.index') }}" class="text-uin hover:text-uin-hover font-bold text-xs inline-flex items-center">
                                    Ajukan Sekarang →
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- 4. BENTO TILE: Nilai Akhir / Warning Profil / Panduan Alur --}}
                    <div class="col-span-1 md:col-span-1 lg:col-span-5 rounded-2xl sm:rounded-3xl p-5 shadow-xs flex flex-col justify-between min-h-[180px] {{ $hasNilaiAkhir ? 'bg-gradient-to-br from-emerald-50 via-teal-50/50 to-white border-2 border-emerald-300/80' : ($hasProfileWarning ? 'bg-gradient-to-br from-amber-50 via-orange-50/50 to-white border-2 border-amber-300/80' : 'bg-white border border-gray-100') }}">
                        @if($hasNilaiAkhir)
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="p-1.5 bg-emerald-600 text-white rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </span>
                                        <h4 class="text-xs font-bold text-gray-900">Nilai Akhir Magang</h4>
                                    </div>
                                    <span class="px-2 py-0.5 bg-emerald-200 text-emerald-900 font-bold text-[10px] rounded-full uppercase tracking-wider">Terbit</span>
                                </div>

                                <div class="flex items-baseline gap-2 mt-1">
                                    <span class="text-3xl font-black text-emerald-700 tracking-tight">{{ number_format($mhsPengajuan->evaluation->nilai_akhir, 2) }}</span>
                                    <span class="text-[11px] text-emerald-800">
                                        (Instansi: {{ number_format($mhsPengajuan->evaluation->nilai_instansi, 2) }} • DPL: {{ number_format($mhsPengajuan->evaluation->nilai_dpl, 2) }})
                                    </span>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-emerald-200/60 space-y-2">
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('mahasiswa.pengajuan.lembar_nilai', $mhsPengajuan->id) }}" target="_blank" class="inline-flex items-center justify-center px-2.5 py-2 bg-emerald-600 hover:bg-emerald-700 active:scale-98 text-white text-[11px] font-bold rounded-xl shadow-xs transition truncate">
                                        <svg class="w-3.5 h-3.5 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span class="truncate">Lembar Nilai</span>
                                    </a>
                                    <a href="{{ route('mahasiswa.logbook.cetak') }}" target="_blank" class="inline-flex items-center justify-center px-2.5 py-2 bg-uin hover:bg-uin-hover active:scale-98 text-white text-[11px] font-bold rounded-xl shadow-xs transition truncate">
                                        <svg class="w-3.5 h-3.5 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        <span class="truncate">Cetak Logbook</span>
                                    </a>
                                </div>
                                @if($mhsPengajuan->evaluation->berkas_nilai_instansi || $mhsPengajuan->evaluation->berkas_nilai_dpl)
                                    <div class="grid grid-cols-2 gap-2 pt-0.5">
                                        @if($mhsPengajuan->evaluation->berkas_nilai_instansi)
                                            <a href="{{ route('evaluasi.berkas_instansi', $mhsPengajuan->id) }}" target="_blank" class="inline-flex items-center justify-center px-2 py-1.5 bg-emerald-100 hover:bg-emerald-200 active:scale-98 text-emerald-900 text-[10px] font-bold rounded-lg border border-emerald-300 transition truncate">
                                                <svg class="w-3 h-3 mr-1 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <span class="truncate">Berkas Instansi ↗</span>
                                            </a>
                                        @endif
                                        @if($mhsPengajuan->evaluation->berkas_nilai_dpl)
                                            <a href="{{ route('evaluasi.berkas_dpl', $mhsPengajuan->id) }}" target="_blank" class="inline-flex items-center justify-center px-2 py-1.5 bg-blue-100 hover:bg-blue-200 active:scale-98 text-blue-900 text-[10px] font-bold rounded-lg border border-blue-300 transition truncate">
                                                <svg class="w-3 h-3 mr-1 text-blue-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <span class="truncate">Berkas DPL ↗</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                        @elseif($hasProfileWarning)
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="p-1.5 bg-amber-500 text-white rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        </span>
                                        <h4 class="text-xs font-bold text-gray-900">Validasi Data Akademik</h4>
                                    </div>
                                    <span class="px-2 py-0.5 bg-amber-200 text-amber-900 font-bold text-[10px] rounded-full uppercase tracking-wider">Wajib Diisi</span>
                                </div>
                                <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                                    Syarat magang: <strong>Min. 110 SKS</strong> & <strong>IPK 2.75</strong>.
                                    Data Anda: <strong>{{ auth()->user()->total_sks ?? 0 }} SKS</strong>, <strong>IPK {{ number_format(auth()->user()->ipk ?? 0, 2) }}</strong>.
                                </p>
                            </div>

                            <div class="pt-3 border-t border-amber-200/60">
                                <a href="{{ route('profile.edit') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-amber-600 hover:bg-amber-700 active:scale-98 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                    Lengkapi Data Profil Sekarang ↗
                                </a>
                            </div>

                        @else
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="p-1.5 bg-uin-light text-uin rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </span>
                                        <h4 class="text-xs font-bold text-gray-900">Alur Magang Aktif</h4>
                                    </div>
                                    <span class="px-2 py-0.5 bg-blue-100 text-uin font-bold text-[10px] rounded-full uppercase tracking-wider">Panduan</span>
                                </div>
                                <div class="space-y-1.5 mt-2">
                                    <div class="flex items-center text-[11px] text-gray-600 gap-2">
                                        <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-[9px] shrink-0">1</span>
                                        <span>Presensi Masuk & Pulang GPS Harian</span>
                                    </div>
                                    <div class="flex items-center text-[11px] text-gray-600 gap-2">
                                        <span class="w-4 h-4 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-[9px] shrink-0">2</span>
                                        <span>Catat aktivitas logbook kegiatan setiap hari</span>
                                    </div>
                                    <div class="flex items-center text-[11px] text-gray-600 gap-2">
                                        <span class="w-4 h-4 rounded-full bg-purple-100 text-purple-700 font-bold flex items-center justify-center text-[9px] shrink-0">3</span>
                                        <span>Evaluasi berkas dan nilai akhir dari DPL & Mitra</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-gray-100 text-[10px] text-gray-400 flex items-center justify-between">
                                <span>GPS Presensi: <strong class="text-emerald-600 font-semibold">Tersinkron</strong></span>
                                <span>UIN Raden Intan Lampung</span>
                            </div>
                        @endif
                    </div>

                    {{-- 5, 6, 7. BENTO ACTION TILES: Quick Action Cards Mahasiswa --}}
                    <div class="col-span-1 md:col-span-1 lg:col-span-4">
                        <a href="{{ route('mahasiswa.pengajuan.index') }}" class="group bg-white rounded-2xl sm:rounded-3xl p-5 border border-gray-100 shadow-xs hover:shadow-lg hover:border-uin active:scale-[0.98] transition-all flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="text-uin bg-uin-light w-11 h-11 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-400 group-hover:text-uin flex items-center transition-colors">
                                        Buka <svg class="w-3.5 h-3.5 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </div>
                                <h4 class="text-sm sm:text-base font-bold text-gray-900 mb-1">Pengajuan & Berkas</h4>
                                <p class="text-xs text-gray-500 leading-relaxed">Pendaftaran magang, cetak surat pengantar prodi, dan unggah balasan instansi.</p>
                            </div>
                            <div class="pt-3 mt-2 border-t border-gray-50 flex items-center justify-between text-[11px] text-gray-400">
                                <span>KTM & Transkrip</span>
                                <span class="font-medium text-uin group-hover:underline">Kelola Berkas →</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-span-1 md:col-span-1 lg:col-span-4">
                        <a href="{{ route('mahasiswa.presensi.index') }}" class="group bg-white rounded-2xl sm:rounded-3xl p-5 border border-gray-100 shadow-xs hover:shadow-lg hover:border-uin active:scale-[0.98] transition-all flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="text-uin bg-uin-light w-11 h-11 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-400 group-hover:text-uin flex items-center transition-colors">
                                        Absen <svg class="w-3.5 h-3.5 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </div>
                                <h4 class="text-sm sm:text-base font-bold text-gray-900 mb-1">Presensi GPS Harian</h4>
                                <p class="text-xs text-gray-500 leading-relaxed">Pencatatan absensi masuk dan pulang otomatis berbasis koordinat GPS lokasi.</p>
                            </div>
                            <div class="pt-3 mt-2 border-t border-gray-50 flex items-center justify-between text-[11px] text-gray-400">
                                <span>Realtime Geolocation</span>
                                <span class="font-medium text-uin group-hover:underline">Presensi Sekarang →</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-span-1 md:col-span-2 lg:col-span-4">
                        <a href="{{ route('mahasiswa.logbook.index') }}" class="group bg-white rounded-2xl sm:rounded-3xl p-5 border border-gray-100 shadow-xs hover:shadow-lg hover:border-uin active:scale-[0.98] transition-all flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="text-uin bg-uin-light w-11 h-11 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-400 group-hover:text-uin flex items-center transition-colors">
                                        Catat <svg class="w-3.5 h-3.5 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </div>
                                <h4 class="text-sm sm:text-base font-bold text-gray-900 mb-1">Logbook Kegiatan</h4>
                                <p class="text-xs text-gray-500 leading-relaxed">Catat aktivitas harian magang dan tinjau catatan feedback bimbingan DPL.</p>
                            </div>
                            <div class="pt-3 mt-2 border-t border-gray-50 flex items-center justify-between text-[11px] text-gray-400">
                                <span>Aktivitas Harian</span>
                                <span class="font-medium text-uin group-hover:underline">Buka Catatan →</span>
                            </div>
                        </a>
                    </div>

                </div>
            @endif

            {{-- ==================== DASHBOARD DOSEN PEMBIMBING (DPL) ==================== --}}
            @if(auth()->user()->role === 'dpl')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3.5 sm:gap-5">
                    {{-- 1. Bento Welcome Banner --}}
                    <div class="col-span-1 md:col-span-2 lg:col-span-8 bg-gradient-to-br from-blue-700 via-uin to-uin-hover text-white rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-sm border border-blue-600/30 relative overflow-hidden flex flex-col justify-between min-h-[180px]">
                        <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="relative z-10 space-y-2">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-white/20 backdrop-blur-sm text-[10px] sm:text-[11px] font-bold tracking-wide uppercase">
                                    Dosen Pembimbing Lapangan (DPL)
                                </span>
                                <span class="text-blue-100 text-[11px] font-medium">
                                    {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}
                                </span>
                            </div>
                            <div class="pt-1">
                                <h3 class="text-xl sm:text-2xl md:text-3xl font-black tracking-tight break-words">
                                    Selamat Datang, {{ auth()->user()->name }}!
                                </h3>
                                <p class="text-blue-100 text-xs sm:text-sm mt-1 max-w-xl leading-relaxed">
                                    Kelola pemantauan mahasiswa magang, review logbook harian, serta evaluasi nilai akhir bimbingan.
                                </p>
                            </div>
                        </div>
                        <div class="relative z-10 pt-4 flex flex-wrap items-center gap-2">
                            <span class="bg-white/15 backdrop-blur-xs px-2.5 py-1 rounded-lg text-xs font-mono text-white">
                                {{ !empty(auth()->user()->nip) ? 'NIP. ' . auth()->user()->nip : (!empty(auth()->user()->nim) ? 'NIDN. ' . auth()->user()->nim : 'Dosen Pembimbing SI') }}
                            </span>
                            <span class="bg-white/15 backdrop-blur-xs px-2.5 py-1 rounded-lg text-xs text-white">
                                Prodi Sistem Informasi
                            </span>
                        </div>
                    </div>

                    {{-- 2. Bento Info Card DPL --}}
                    <div class="col-span-1 md:col-span-2 lg:col-span-4 bg-white rounded-2xl sm:rounded-3xl p-5 border border-gray-100 shadow-xs flex flex-col justify-between min-h-[180px]">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-black uppercase tracking-wider text-gray-500">Peran & Bimbingan</span>
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full">DPL Aktif</span>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900 mt-2">Bimbingan & Pemantauan</h4>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                Anda memiliki wewenang untuk memeriksa logbook, memvalidasi absensi GPS, dan menginput lembar penilaian akhir.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                            <span>Sistem Penilaian</span>
                            <span class="font-bold text-uin">Terintegrasi Instansi</span>
                        </div>
                    </div>

                    {{-- 3 & 4. Bento Action Cards DPL --}}
                    <div class="col-span-1 md:col-span-1 lg:col-span-6">
                        <a href="{{ route('dpl.pemantauan.index') }}" class="group bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-xs hover:shadow-lg hover:border-uin active:scale-[0.98] transition-all flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-center justify-between mb-3 sm:mb-4">
                                    <div class="text-uin bg-uin-light w-12 h-12 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-400 group-hover:text-uin flex items-center transition-colors">
                                        Buka Pemantauan <svg class="w-3.5 h-3.5 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </div>
                                <h4 class="text-base font-bold text-gray-900 mb-1">Pemantauan & Logbook</h4>
                                <p class="text-xs text-gray-500 leading-relaxed">Pantau presensi GPS, periksa catatan logbook harian, dan beri tanggapan bimbingan mahasiswa.</p>
                            </div>
                            <div class="pt-4 mt-3 border-t border-gray-50 flex items-center justify-between text-xs text-gray-400">
                                <span>Presensi GPS & Catatan Harian</span>
                                <span class="font-bold text-uin group-hover:underline">Buka Data →</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-span-1 md:col-span-1 lg:col-span-6">
                        <a href="{{ route('dpl.evaluasi.index') }}" class="group bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-6 border border-gray-100 shadow-xs hover:shadow-lg hover:border-uin active:scale-[0.98] transition-all flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-center justify-between mb-3 sm:mb-4">
                                    <div class="text-uin bg-uin-light w-12 h-12 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-400 group-hover:text-uin flex items-center transition-colors">
                                        Input Penilaian <svg class="w-3.5 h-3.5 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </div>
                                <h4 class="text-base font-bold text-gray-900 mb-1">Evaluasi & Penilaian</h4>
                                <p class="text-xs text-gray-500 leading-relaxed">Input nilai DPL dan nilai Instansi mitra, kalkulasi otomatis, serta terbitkan lembar nilai resmi.</p>
                            </div>
                            <div class="pt-4 mt-3 border-t border-gray-50 flex items-center justify-between text-xs text-gray-400">
                                <span>Nilai Instansi & Nilai DPL</span>
                                <span class="font-bold text-uin group-hover:underline">Beri Nilai →</span>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

            {{-- ==================== DASHBOARD ADMIN PRODI SI ==================== --}}
            @if(auth()->user()->role === 'admin')
                @php
                    $totalMahasiswa = \App\Models\User::where('role', 'mahasiswa')->count();
                    $totalDpl = \App\Models\User::where('role', 'dpl')->count();
                    $menungguVerifikasi = \App\Models\MagangApplication::where('status', 'pending')->count();
                    $magangAktif = \App\Models\MagangApplication::whereIn('status', ['approved', 'active'])->count();
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-3.5 sm:gap-5">
                    {{-- 1. Bento Welcome Banner Admin --}}
                    <div class="col-span-1 md:col-span-2 lg:col-span-8 bg-gradient-to-br from-blue-700 via-uin to-uin-hover text-white rounded-2xl sm:rounded-3xl p-5 sm:p-6 shadow-sm border border-blue-600/30 relative overflow-hidden flex flex-col justify-between min-h-[180px]">
                        <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="relative z-10 space-y-2">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-white/20 backdrop-blur-sm text-[10px] sm:text-[11px] font-bold tracking-wide uppercase">
                                    Admin Program Studi Sistem Informasi
                                </span>
                                <span class="text-blue-100 text-[11px] font-medium">
                                    {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}
                                </span>
                            </div>
                            <div class="pt-1">
                                <h3 class="text-xl sm:text-2xl md:text-3xl font-black tracking-tight break-words">
                                    Selamat Datang, {{ auth()->user()->name }}!
                                </h3>
                                <p class="text-blue-100 text-xs sm:text-sm mt-1 max-w-xl leading-relaxed">
                                    Kelola verifikasi pengajuan berkas, penetapan DPL, pemantauan kegiatan magang, serta rekapitulasi nilai prodi.
                                </p>
                            </div>
                        </div>
                        <div class="relative z-10 pt-4 flex flex-wrap items-center gap-2">
                            <span class="bg-white/15 backdrop-blur-xs px-2.5 py-1 rounded-lg text-xs font-medium text-white">
                                Fakultas Sains dan Teknologi
                            </span>
                            <span class="bg-white/15 backdrop-blur-xs px-2.5 py-1 rounded-lg text-xs font-medium text-white">
                                SIMAMA v1.0
                            </span>
                        </div>
                    </div>

                    {{-- 2. Bento Info Tile Admin --}}
                    <div class="col-span-1 md:col-span-2 lg:col-span-4 bg-white rounded-2xl sm:rounded-3xl p-5 border border-gray-100 shadow-xs flex flex-col justify-between min-h-[180px]">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-black uppercase tracking-wider text-gray-500">Status Sistem</span>
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full">Aktif & Stabil</span>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900 mt-2">Ringkasan Magang Prodi</h4>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                Pengawasan data pendaftaran, kelayakan berkas KTM & Transkrip, serta pembagian dosen pembimbing mahasiswa SI.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400">
                            <span>Tahun Akademik</span>
                            <span class="font-bold text-uin">2026/2027</span>
                        </div>
                    </div>

                    {{-- 3, 4, 5, 6. Bento Stat Tiles (4 stats) --}}
                    <div class="col-span-1 sm:col-span-1 md:col-span-1 lg:col-span-3 bg-white p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-xs hover:shadow-md transition-all flex items-center justify-between">
                        <div>
                            <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider">Mahasiswa SI</p>
                            <p class="text-2xl sm:text-3xl font-black text-uin mt-0.5 sm:mt-1">{{ $totalMahasiswa }}</p>
                            <p class="text-[10px] text-gray-400 mt-1">Terdaftar di sistem</p>
                        </div>
                        <div class="p-3 bg-uin-light text-uin rounded-2xl shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>

                    <div class="col-span-1 sm:col-span-1 md:col-span-1 lg:col-span-3 bg-white p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-xs hover:shadow-md transition-all flex items-center justify-between">
                        <div>
                            <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider">Perlu Verifikasi</p>
                            <p class="text-2xl sm:text-3xl font-black text-amber-500 mt-0.5 sm:mt-1">{{ $menungguVerifikasi }}</p>
                            <p class="text-[10px] text-gray-400 mt-1">Menunggu approval</p>
                        </div>
                        <div class="p-3 bg-amber-50 text-amber-600 rounded-2xl shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>

                    <div class="col-span-1 sm:col-span-1 md:col-span-1 lg:col-span-3 bg-white p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-xs hover:shadow-md transition-all flex items-center justify-between">
                        <div>
                            <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider">Magang Berjalan</p>
                            <p class="text-2xl sm:text-3xl font-black text-emerald-600 mt-0.5 sm:mt-1">{{ $magangAktif }}</p>
                            <p class="text-[10px] text-gray-400 mt-1">Aktif di mitra</p>
                        </div>
                        <div class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>

                    <div class="col-span-1 sm:col-span-1 md:col-span-1 lg:col-span-3 bg-white p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-gray-100 shadow-xs hover:shadow-md transition-all flex items-center justify-between">
                        <div>
                            <p class="text-[10px] sm:text-xs font-bold text-gray-400 uppercase tracking-wider">DPL Terdaftar</p>
                            <p class="text-2xl sm:text-3xl font-black text-blue-600 mt-0.5 sm:mt-1">{{ $totalDpl }}</p>
                            <p class="text-[10px] text-gray-400 mt-1">Dosen pembimbing</p>
                        </div>
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                    </div>

                    {{-- 7, 8, 9, 10. Bento Action Cards Admin --}}
                    <div class="col-span-1 sm:col-span-1 md:col-span-1 lg:col-span-3">
                        <a href="{{ route('admin.verifikasi.index') }}" class="group bg-white rounded-2xl sm:rounded-3xl p-5 border border-gray-100 shadow-xs hover:shadow-lg hover:border-uin active:scale-[0.98] transition-all flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="text-uin bg-uin-light w-11 h-11 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-400 group-hover:text-uin flex items-center transition-colors">
                                        Verifikasi <svg class="w-3.5 h-3.5 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </div>
                                <h4 class="text-sm sm:text-base font-bold text-gray-900 mb-1">Verifikasi Pengajuan</h4>
                                <p class="text-xs text-gray-500 leading-relaxed">Tinjau kelayakan berkas KTM & Transkrip mahasiswa SI.</p>
                            </div>
                            <div class="pt-3 mt-2 border-t border-gray-50 text-[11px] text-gray-400 flex items-center justify-between">
                                <span>KTM & Transkrip</span>
                                <span class="font-bold text-uin group-hover:underline">Tinjau →</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-span-1 sm:col-span-1 md:col-span-1 lg:col-span-3">
                        <a href="{{ route('admin.penetapan_dpl.index') }}" class="group bg-white rounded-2xl sm:rounded-3xl p-5 border border-gray-100 shadow-xs hover:shadow-lg hover:border-uin active:scale-[0.98] transition-all flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="text-uin bg-uin-light w-11 h-11 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-400 group-hover:text-uin flex items-center transition-colors">
                                        Plotting <svg class="w-3.5 h-3.5 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </div>
                                <h4 class="text-sm sm:text-base font-bold text-gray-900 mb-1">Penetapan DPL</h4>
                                <p class="text-xs text-gray-500 leading-relaxed">Tetapkan Dosen Pembimbing Lapangan (DPL) mahasiswa magang.</p>
                            </div>
                            <div class="pt-3 mt-2 border-t border-gray-50 text-[11px] text-gray-400 flex items-center justify-between">
                                <span>Alokasi Bimbingan</span>
                                <span class="font-bold text-uin group-hover:underline">Atur DPL →</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-span-1 sm:col-span-1 md:col-span-1 lg:col-span-3">
                        <a href="{{ route('admin.rekap.index') }}" class="group bg-white rounded-2xl sm:rounded-3xl p-5 border border-gray-100 shadow-xs hover:shadow-lg hover:border-uin active:scale-[0.98] transition-all flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="text-uin bg-uin-light w-11 h-11 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-400 group-hover:text-uin flex items-center transition-colors">
                                        Rekap <svg class="w-3.5 h-3.5 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </div>
                                <h4 class="text-sm sm:text-base font-bold text-gray-900 mb-1">Rekap Nilai Akhir</h4>
                                <p class="text-xs text-gray-500 leading-relaxed">Rekapitulasi seluruh nilai magang dan ekspor PDF Prodi.</p>
                            </div>
                            <div class="pt-3 mt-2 border-t border-gray-50 text-[11px] text-gray-400 flex items-center justify-between">
                                <span>Ekspor PDF Nilai</span>
                                <span class="font-bold text-uin group-hover:underline">Buka Rekap →</span>
                            </div>
                        </a>
                    </div>

                    <div class="col-span-1 sm:col-span-1 md:col-span-1 lg:col-span-3">
                        <a href="{{ route('admin.users.index') }}" class="group bg-white rounded-2xl sm:rounded-3xl p-5 border border-gray-100 shadow-xs hover:shadow-lg hover:border-uin active:scale-[0.98] transition-all flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="text-uin bg-uin-light w-11 h-11 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-400 group-hover:text-uin flex items-center transition-colors">
                                        Kelola <svg class="w-3.5 h-3.5 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </div>
                                <h4 class="text-sm sm:text-base font-bold text-gray-900 mb-1">Manajemen Pengguna</h4>
                                <p class="text-xs text-gray-500 leading-relaxed">Kelola akun Dosen Pembimbing (DPL) & Mahasiswa SI.</p>
                            </div>
                            <div class="pt-3 mt-2 border-t border-gray-50 text-[11px] text-gray-400 flex items-center justify-between">
                                <span>Akun Mahasiswa & DPL</span>
                                <span class="font-bold text-uin group-hover:underline">Kelola Akun →</span>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>