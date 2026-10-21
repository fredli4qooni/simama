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

    <div class="py-8">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">

            <!-- Welcome Banner -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="relative p-8 bg-gradient-to-r from-blue-700 via-uin to-uin-hover text-white overflow-hidden">
                    <!-- Subtle Decorative Background Circles -->
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute left-1/2 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="space-y-2 text-center md:text-left">
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-xs font-semibold tracking-wide uppercase">
                                Program Studi Sistem Informasi
                            </div>
                            <h3 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                                Selamat Datang, {{ auth()->user()->name }}!
                            </h3>
                            <p class="text-blue-100 text-sm max-w-xl leading-relaxed">
                                Sistem Informasi Manajemen Kerja Praktik & Magang Mahasiswa (SIMAMA) Program Studi Sistem Informasi UIN Raden Intan Lampung.
                            </p>
                        </div>

                        <!-- Icon Badge Container -->
                        <div class="flex-shrink-0 flex items-center justify-center p-4 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl ring-4 ring-white/20 hover:scale-105 transition-transform duration-300">
                            <img src="https://img.icons8.com/external-itim2101-blue-itim2101/64/external-books-online-education-itim2101-blue-itim2101.png" 
                                 alt="SIMAMA Logo" 
                                 class="w-16 h-16 object-contain drop-shadow-sm" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFOBOX JIKA MAHASISWA BELUM MENGISI NIM / SKS & IPK -->
            @if(auth()->user()->role === 'mahasiswa' && (empty(auth()->user()->nim) || empty(auth()->user()->total_sks) || empty(auth()->user()->ipk) || auth()->user()->total_sks < 110 || auth()->user()->ipk < 2.75))
                <div class="bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-amber-500/10 border-2 border-amber-400/80 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 bg-amber-500 text-white rounded-2xl shadow-md flex-shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <h4 class="text-base font-bold text-gray-900">Perhatian: Lengkapi NIM & Data Akademik</h4>
                                <span class="px-2 py-0.5 bg-amber-200 text-amber-900 font-bold text-[10px] rounded-full uppercase tracking-wider">Wajib Diisi</span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed max-w-2xl">
                                @if(empty(auth()->user()->nim) || empty(auth()->user()->total_sks) || empty(auth()->user()->ipk))
                                    Anda belum melengkapi data <strong>NIM/NPM</strong>, <strong>Total SKS</strong>, atau <strong>IPK Kumulatif</strong> di profil akun Anda. Data ini diperlukan untuk memvalidasi kelayakan pendaftaran magang (Syarat: Min. 110 SKS (setara semester 6 ke atas) & IPK 2.75).
                                @else
                                    Data akademik Anda saat ini (<strong>{{ auth()->user()->total_sks }} SKS</strong>, <strong>IPK {{ number_format(auth()->user()->ipk, 2) }}</strong>) belum memenuhi batas minimum persyaratan magang (Min. 110 SKS & IPK 2.75).
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="w-full md:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-md transition-all whitespace-nowrap">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Lengkapi Data Profil Sekarang
                    </a>
                </div>
            @endif

            <!-- INFOBOX JIKA NILAI AKHIR MAGANG SUDAH TERBIT -->
            @php
                $mhsPengajuan = auth()->user()->role === 'mahasiswa' ? auth()->user()->latestMagangApplication() : null;
            @endphp
            @if($mhsPengajuan && $mhsPengajuan->evaluation)
                <div class="bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border-2 border-emerald-300 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-emerald-600 text-white rounded-2xl shadow-md flex-shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="text-base font-bold text-gray-900">Nilai Akhir Magang Anda Telah Terbit!</h4>
                                <span class="px-2.5 py-0.5 bg-emerald-200 text-emerald-900 font-bold text-[10px] rounded-full uppercase tracking-wider">Selesai</span>
                            </div>
                            <p class="text-xs text-emerald-800 mt-1">
                                Instansi: <strong>{{ $mhsPengajuan->instansi }}</strong> | 
                                Nilai Akhir: <strong class="text-base font-black text-emerald-700">{{ number_format($mhsPengajuan->evaluation->nilai_akhir, 2) }}</strong>
                                (Nilai Instansi: {{ number_format($mhsPengajuan->evaluation->nilai_instansi, 2) }}, Nilai DPL: {{ number_format($mhsPengajuan->evaluation->nilai_dpl, 2) }})
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
                        <a href="{{ route('mahasiswa.pengajuan.lembar_nilai', $mhsPengajuan->id) }}" target="_blank" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Unduh Lembar Nilai (PDF)
                        </a>
                        <a href="{{ route('mahasiswa.logbook.cetak') }}" target="_blank" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-uin hover:bg-uin-hover text-white text-xs font-bold rounded-xl shadow-md transition whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Logbook Resmi (PDF)
                        </a>
                        @if($mhsPengajuan->evaluation->berkas_nilai_instansi)
                            <a href="{{ route('evaluasi.berkas_instansi', $mhsPengajuan->id) }}" target="_blank" class="w-full md:w-auto inline-flex items-center justify-center px-3.5 py-2.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-900 text-xs font-bold rounded-xl border border-emerald-300 transition whitespace-nowrap shadow-xs">
                                <svg class="w-4 h-4 mr-1.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Berkas Nilai Instansi ↗
                            </a>
                        @endif
                        @if($mhsPengajuan->evaluation->berkas_nilai_dpl)
                            <a href="{{ route('evaluasi.berkas_dpl', $mhsPengajuan->id) }}" target="_blank" class="w-full md:w-auto inline-flex items-center justify-center px-3.5 py-2.5 bg-blue-100 hover:bg-blue-200 text-blue-900 text-xs font-bold rounded-xl border border-blue-300 transition whitespace-nowrap shadow-xs">
                                <svg class="w-4 h-4 mr-1.5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Berkas Nilai DPL ↗
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- INFORMASI STATUS MAGANG & DOSEN PEMBIMBING (DPL) -->
            @if(auth()->user()->role === 'mahasiswa' && $mhsPengajuan)
                @php
                    $mhsDpl = $mhsPengajuan->dplAssignment->dpl ?? null;
                @endphp
                <div class="bg-white overflow-hidden shadow-xs rounded-2xl border border-gray-100 p-6">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                        <!-- Informasi Instansi & Status Magang -->
                        <div class="space-y-2.5 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full {{ $mhsPengajuan->jenis_magang === 'mandiri' ? 'bg-purple-100 text-purple-800' : 'bg-uin-light text-uin' }}">
                                    {{ $mhsPengajuan->jenis_magang === 'mandiri' ? 'Magang Mandiri' : 'Magang Program Studi' }}
                                </span>
                                @if($mhsPengajuan->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-amber-50 text-amber-800 rounded-full text-[10px] font-bold border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span> Menunggu Verifikasi Berkas
                                    </span>
                                @elseif($mhsPengajuan->status == 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-blue-50 text-blue-800 rounded-full text-[10px] font-bold border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5 animate-pulse"></span> Disetujui (Menunggu Penetapan DPL)
                                    </span>
                                @elseif($mhsPengajuan->status == 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-emerald-50 text-emerald-800 rounded-full text-[10px] font-bold border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-ping"></span> Magang Berjalan Aktif
                                    </span>
                                @elseif($mhsPengajuan->status == 'selesai')
                                    <span class="inline-flex items-center px-2.5 py-0.5 bg-purple-50 text-purple-800 rounded-full text-[10px] font-bold border border-purple-200">
                                        ✓ Kegiatan Selesai & Terverifikasi
                                    </span>
                                @endif
                            </div>
                            
                            <div>
                                <h3 class="text-xl font-black text-gray-900 tracking-tight">{{ $mhsPengajuan->instansi }}</h3>
                                <p class="text-xs text-gray-500 flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    {{ $mhsPengajuan->alamat ?? $mhsPengajuan->alamat_instansi ?? 'Alamat instansi mitra' }}
                                </p>
                            </div>

                            <p class="text-xs text-gray-500 font-mono">
                                📅 {{ \Carbon\Carbon::parse($mhsPengajuan->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($mhsPengajuan->end_date)->format('d M Y') }}
                            </p>
                        </div>

                        <!-- Profil DPL Card -->
                        <div class="w-full lg:w-80 p-4 rounded-2xl border {{ $mhsDpl ? 'bg-gradient-to-br from-blue-50/80 to-indigo-50/50 border-blue-200/90 shadow-2xs' : 'bg-gray-50/80 border-gray-200/80' }}">
                            <div class="flex items-center justify-between mb-2.5">
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
                                <div class="flex items-center space-x-3">
                                    @if($mhsDpl->avatar_url)
                                        <img src="{{ $mhsDpl->avatar_url }}" alt="{{ $mhsDpl->name }}" class="w-11 h-11 rounded-full object-cover ring-2 ring-blue-500/30 shadow-xs flex-shrink-0">
                                    @else
                                        <div class="w-11 h-11 rounded-full bg-uin text-white font-bold flex items-center justify-center text-sm shadow-xs flex-shrink-0" style="background-color: #1d4ed8;">
                                            {{ strtoupper(substr($mhsDpl->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="overflow-hidden min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <p class="font-bold text-gray-900 text-xs truncate" title="{{ $mhsDpl->name }}">{{ $mhsDpl->name }}</p>
                                            <span class="inline-flex items-center text-blue-600 flex-shrink-0" title="Dosen Pembimbing Lapangan Resmi">
                                                <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                        <p class="text-gray-600 text-[11px] font-mono truncate">
                                            {{ !empty($mhsDpl->nip) ? 'NIP. ' . $mhsDpl->nip : (!empty($mhsDpl->nim) ? 'NIDN. ' . $mhsDpl->nim : '') }}
                                        </p>
                                        <p class="text-gray-500 text-[10px] truncate flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            <span>{{ $mhsDpl->email }}</span>
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center space-x-3 py-1 text-gray-500">
                                    <div class="w-10 h-10 rounded-full bg-gray-200/90 text-gray-400 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-700 text-xs">DPL Belum Ditetapkan</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5 leading-tight">Admin Prodi akan menetapkan DPL setelah berkas lengkap.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Dashboard Mahasiswa -->
            @if(auth()->user()->role === 'mahasiswa')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('mahasiswa.pengajuan.index') }}" class="group bg-white overflow-hidden shadow-xs rounded-2xl p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-uin">
                        <div class="text-uin mb-4 bg-uin-light w-12 h-12 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">Pengajuan & Berkas</h4>
                        <p class="text-xs text-gray-500">Pendaftaran magang, cetak surat pengantar prodi, dan unggah balasan instansi.</p>
                    </a>

                    <a href="{{ route('mahasiswa.presensi.index') }}" class="group bg-white overflow-hidden shadow-xs rounded-2xl p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-uin">
                        <div class="text-uin mb-4 bg-uin-light w-12 h-12 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">Presensi GPS Harian</h4>
                        <p class="text-xs text-gray-500">Pencatatan absensi masuk dan pulang otomatis berbasis koordinat GPS lokasi.</p>
                    </a>

                    <a href="{{ route('mahasiswa.logbook.index') }}" class="group bg-white overflow-hidden shadow-xs rounded-2xl p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-uin">
                        <div class="text-uin mb-4 bg-uin-light w-12 h-12 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">Logbook Kegiatan</h4>
                        <p class="text-xs text-gray-500">Catat aktivitas harian magang dan tinjau catatan feedback DPL.</p>
                    </a>
                </div>
            @endif

            <!-- Dashboard DPL -->
            @if(auth()->user()->role === 'dpl')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('dpl.pemantauan.index') }}" class="group bg-white overflow-hidden shadow-xs rounded-2xl p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-uin">
                        <div class="text-uin mb-4 bg-uin-light w-12 h-12 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">Pemantauan & Logbook</h4>
                        <p class="text-xs text-gray-500">Pantau presensi GPS, periksa catatan logbook harian, dan beri tanggapan bimbingan.</p>
                    </a>

                    <a href="{{ route('dpl.evaluasi.index') }}" class="group bg-white overflow-hidden shadow-xs rounded-2xl p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-uin">
                        <div class="text-uin mb-4 bg-uin-light w-12 h-12 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">Evaluasi & Penilaian</h4>
                        <p class="text-xs text-gray-500">Input nilai DPL dan nilai Instansi, kalkulasi nilai rata-rata, dan cetak lembar penilaian.</p>
                    </a>
                </div>
            @endif

            <!-- Dashboard Admin Program Studi Sistem Informasi -->
            @if(auth()->user()->role === 'admin')
                @php
                    $totalMahasiswa = \App\Models\User::where('role', 'mahasiswa')->count();
                    $totalDpl = \App\Models\User::where('role', 'dpl')->count();
                    $menungguVerifikasi = \App\Models\MagangApplication::where('status', 'pending')->count();
                    $magangAktif = \App\Models\MagangApplication::whereIn('status', ['approved', 'active'])->count();
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Mahasiswa SI</p>
                                <p class="text-3xl font-black text-uin mt-1">{{ $totalMahasiswa }}</p>
                            </div>
                            <div class="p-3 bg-uin-light text-uin rounded-2xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Perlu Verifikasi</p>
                                <p class="text-3xl font-black text-amber-500 mt-1">{{ $menungguVerifikasi }}</p>
                            </div>
                            <div class="p-3 bg-amber-50 text-amber-600 rounded-2xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Magang Berjalan</p>
                                <p class="text-3xl font-black text-emerald-600 mt-1">{{ $magangAktif }}</p>
                            </div>
                            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-2xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:shadow-md transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">DPL Terdaftar</p>
                                <p class="text-3xl font-black text-blue-600 mt-1">{{ $totalDpl }}</p>
                            </div>
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MENU AKSI CEPAT ADMIN PRODI SI -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <a href="{{ route('admin.verifikasi.index') }}" class="group bg-white overflow-hidden shadow-xs rounded-2xl p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-uin">
                        <div class="text-uin mb-4 bg-uin-light w-12 h-12 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">Verifikasi Pengajuan</h4>
                        <p class="text-xs text-gray-500">Tinjau kelayakan berkas KTM & Transkrip mahasiswa SI.</p>
                    </a>

                    <a href="{{ route('admin.penetapan_dpl.index') }}" class="group bg-white overflow-hidden shadow-xs rounded-2xl p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-uin">
                        <div class="text-uin mb-4 bg-uin-light w-12 h-12 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">Penetapan DPL</h4>
                        <p class="text-xs text-gray-500">Tetapkan Dosen Pembimbing Lapangan (DPL) mahasiswa magang.</p>
                    </a>

                    <a href="{{ route('admin.rekap.index') }}" class="group bg-white overflow-hidden shadow-xs rounded-2xl p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-uin">
                        <div class="text-uin mb-4 bg-uin-light w-12 h-12 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">Rekap Nilai Akhir</h4>
                        <p class="text-xs text-gray-500">Rekapitulasi seluruh nilai magang dan ekspor PDF Prodi.</p>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="group bg-white overflow-hidden shadow-xs rounded-2xl p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-uin">
                        <div class="text-uin mb-4 bg-uin-light w-12 h-12 flex items-center justify-center rounded-xl group-hover:bg-uin group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">Manajemen Pengguna</h4>
                        <p class="text-xs text-gray-500">Kelola akun Dosen Pembimbing (DPL) & Mahasiswa SI.</p>
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>