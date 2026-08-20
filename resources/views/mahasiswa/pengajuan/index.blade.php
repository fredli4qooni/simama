<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight flex items-center gap-2">
                    <span class="p-2 bg-uin-light text-uin rounded-lg shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </span>
                    Pengajuan & Dokumen Magang
                </h2>
                <p class="text-sm text-gray-500 mt-1">Pendaftaran kelompok magang, unduh surat pengantar prodi, unggah surat balasan, dan pelaporan akhir.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- ALERT NOTIFIKASI -->
            @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl shadow-sm font-medium flex items-center justify-between text-emerald-800 text-sm">
                <div class="flex items-center">
                    <div class="p-2 bg-emerald-100 rounded-lg mr-3 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if (session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl shadow-sm font-medium flex items-center justify-between text-rose-800 text-sm">
                <div class="flex items-center">
                    <div class="p-2 bg-rose-100 rounded-lg mr-3 text-rose-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <!-- 1. JOURNEY STEPPER (TAHAPAN STATUS MAGANG) -->
            @php
                $step = 1;
                if ($pengajuan) {
                    if ($pengajuan->status == 'pending') $step = 2;
                    elseif ($pengajuan->status == 'approved') $step = 3;
                    elseif ($pengajuan->status == 'active') $step = 4;
                    elseif ($pengajuan->status == 'selesai') $step = 5;
                }
                $isAcademicEligible = (auth()->user()->total_sks >= 100 && auth()->user()->ipk >= 2.50);
            @endphp

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <h4 class="text-xs font-black tracking-widest text-gray-400 uppercase mb-5 flex items-center">
                    <svg class="w-4 h-4 mr-1.5 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Alur Proses Magang Anda
                </h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <!-- Step 1 -->
                    <div class="p-3.5 rounded-xl border {{ $step >= 1 ? ($step == 1 ? 'bg-uin-light/40 border-uin text-uin' : 'bg-gray-50 border-gray-200 text-gray-800') : 'bg-gray-50 border-gray-100 text-gray-400 opacity-60' }}">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $step >= 1 ? 'bg-uin text-white' : 'bg-gray-200 text-gray-600' }}">1</span>
                            <span class="text-xs font-bold">Pendaftaran</span>
                        </div>
                        <p class="text-[11px] text-gray-500 line-clamp-2">Input berkas kelompok, KTM & Transkrip.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="p-3.5 rounded-xl border {{ $step >= 2 ? ($step == 2 ? 'bg-uin-light/40 border-uin text-uin' : 'bg-gray-50 border-gray-200 text-gray-800') : 'bg-gray-50 border-gray-100 text-gray-400 opacity-60' }}">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $step >= 2 ? 'bg-uin text-white' : 'bg-gray-200 text-gray-600' }}">2</span>
                            <span class="text-xs font-bold">Surat Pengantar</span>
                        </div>
                        <p class="text-[11px] text-gray-500 line-clamp-2">Cetak format tanpa TTD & cap manual Prodi.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="p-3.5 rounded-xl border {{ $step >= 3 ? ($step == 3 ? 'bg-uin-light/40 border-uin text-uin' : 'bg-gray-50 border-gray-200 text-gray-800') : 'bg-gray-50 border-gray-100 text-gray-400 opacity-60' }}">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $step >= 3 ? 'bg-uin text-white' : 'bg-gray-200 text-gray-600' }}">3</span>
                            <span class="text-xs font-bold">Surat Balasan</span>
                        </div>
                        <p class="text-[11px] text-gray-500 line-clamp-2">Unggah bukti penerimaan dari instansi.</p>
                    </div>

                    <!-- Step 4 -->
                    <div class="p-3.5 rounded-xl border {{ $step >= 4 ? ($step == 4 ? 'bg-uin-light/40 border-uin text-uin' : 'bg-gray-50 border-gray-200 text-gray-800') : 'bg-gray-50 border-gray-100 text-gray-400 opacity-60' }}">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $step >= 4 ? 'bg-uin text-white' : 'bg-gray-200 text-gray-600' }}">4</span>
                            <span class="text-xs font-bold">Magang & DPL</span>
                        </div>
                        <p class="text-[11px] text-gray-500 line-clamp-2">DPL ditetapkan, presensi GPS & logbook aktif.</p>
                    </div>

                    <!-- Step 5 -->
                    <div class="p-3.5 rounded-xl border {{ $step == 5 ? 'bg-uin-light/40 border-uin text-uin' : 'bg-gray-50 border-gray-100 text-gray-400 opacity-60' }}">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $step == 5 ? 'bg-uin text-white' : 'bg-gray-200 text-gray-600' }}">5</span>
                            <span class="text-xs font-bold">Laporan & Nilai</span>
                        </div>
                        <p class="text-[11px] text-gray-500 line-clamp-2">Unggah laporan akhir & penerimaan nilai.</p>
                    </div>
                </div>
            </div>

            <!-- 2. JIKA PENGAJUAN SUDAH ADA (DASHBOARD KELOMPOK & DOKUMEN HUB) -->
            @if($pengajuan && in_array($pengajuan->status, ['pending', 'approved', 'active', 'selesai']))
            
            <!-- HERO STATUS CARD -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 md:p-8 bg-gradient-to-br from-gray-900 via-slate-900 to-gray-800 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 text-xs font-black rounded-md tracking-wider uppercase {{ $pengajuan->is_group ? 'bg-indigo-500 text-white' : 'bg-gray-600 text-white' }}">
                                {{ $pengajuan->is_group ? '👥 Magang Kelompok' : '👤 Magang Individu' }}
                            </span>
                            <span class="px-2.5 py-1 text-xs font-black rounded-md tracking-wider uppercase bg-white/10 text-gray-200">
                                {{ strtoupper($pengajuan->jenis_magang) }}
                            </span>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black">{{ $pengajuan->instansi }}</h3>
                        <p class="text-xs text-gray-300 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-uin-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            {{ $pengajuan->alamat ?? $pengajuan->alamat_instansi }}
                        </p>
                    </div>

                    <div class="flex flex-col items-start md:items-end gap-2 bg-white/10 p-4 rounded-xl backdrop-blur-sm border border-white/10">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Saat Ini</span>
                        @if($pengajuan->status == 'pending')
                            <span class="inline-flex items-center px-3 py-1 bg-amber-400/20 text-amber-300 rounded-full text-xs font-bold border border-amber-400/30">
                                <span class="w-2 h-2 rounded-full bg-amber-400 mr-2 animate-pulse"></span> Menunggu Verifikasi & TTD Prodi
                            </span>
                        @elseif($pengajuan->status == 'approved')
                            <span class="inline-flex items-center px-3 py-1 bg-blue-400/20 text-blue-300 rounded-full text-xs font-bold border border-blue-400/30">
                                <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 animate-pulse"></span> Disetujui Prodi (Unggah Surat Balasan)
                            </span>
                        @elseif($pengajuan->status == 'active')
                            <span class="inline-flex items-center px-3 py-1 bg-emerald-400/20 text-emerald-300 rounded-full text-xs font-bold border border-emerald-400/30">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-ping"></span> Magang Sedang Aktif
                            </span>
                        @elseif($pengajuan->status == 'selesai')
                            <span class="inline-flex items-center px-3 py-1 bg-purple-400/20 text-purple-300 rounded-full text-xs font-bold border border-purple-400/30">
                                Selesai & Terverifikasi
                            </span>
                        @endif
                        <span class="text-[11px] text-gray-300 font-mono mt-1">
                            📅 {{ \Carbon\Carbon::parse($pengajuan->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($pengajuan->end_date)->format('d M Y') }}
                        </span>
                    </div>
                </div>

                <!-- DAFTAR ANGGOTA KELOMPOK (CARD GRID) -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h4 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Susunan Anggota Kelompok
                    </h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @if($pengajuan->members && $pengajuan->members->count() > 0)
                            @foreach($pengajuan->members as $member)
                                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between space-y-3">
                                    <div class="flex items-center space-x-3">
                                        @if($member->user->avatar_url)
                                            <img src="{{ $member->user->avatar_url }}" alt="{{ $member->user->name }}" class="w-10 h-10 rounded-full object-cover shadow-sm ring-1 ring-uin/30 flex-shrink-0">
                                        @else
                                            <div class="w-10 h-10 rounded-full {{ $member->is_leader ? 'bg-uin text-white font-black' : 'bg-gray-100 text-gray-700 font-bold' }} flex items-center justify-center text-sm shadow-sm flex-shrink-0">
                                                {{ substr($member->user->name ?? 'M', 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="overflow-hidden">
                                            <p class="text-xs font-bold text-gray-900 truncate">{{ $member->user->name ?? '-' }}</p>
                                            <p class="text-[11px] text-gray-500 font-mono">{{ $member->user->nim ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $member->is_leader ? 'bg-uin-light text-uin' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $member->is_leader ? 'Ketua Kelompok' : 'Anggota' }}
                                        </span>
                                        <span class="text-[10px] text-emerald-600 font-semibold flex items-center">
                                            <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Berkas OK
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-uin text-white font-black flex items-center justify-center text-sm">
                                    {{ substr($pengajuan->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-900">{{ $pengajuan->user->name }}</p>
                                    <p class="text-[11px] text-gray-500 font-mono">{{ $pengajuan->user->nim }} (Pemohon Tunggal)</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 3. PUSAT BERKAS & DOKUMEN AKSI (DOCUMENT HUB - 3 KARTU TERINTEGRASI) -->
                <div class="p-6 md:p-8 space-y-6">
                    <h4 class="text-xs font-black uppercase tracking-wider text-gray-400 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                        </svg>
                        Pusat Aksi & Pengelolaan Berkas
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <!-- KARTU 1: SURAT PENGANTAR PRODI -->
                        <div class="bg-gradient-to-br from-amber-50/60 to-orange-50/40 p-6 rounded-2xl border border-amber-200/80 flex flex-col justify-between space-y-4 hover:shadow-md transition-all">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="p-2.5 bg-amber-100 text-amber-700 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </span>
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full bg-amber-200 text-amber-900">Format Tanpa TTD</span>
                                </div>
                                <h5 class="font-bold text-gray-900 text-base">1. Surat Pengantar Prodi</h5>
                                <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                                    Cetak surat pengantar ini lalu mintakan <strong>tanda tangan manual & stempel</strong> ke pihak Program Studi sebelum diserahkan ke instansi.
                                </p>
                            </div>
                            <a href="{{ route('mahasiswa.pengajuan.surat_pengantar', $pengajuan->id) }}" target="_blank" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-sm transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Unduh Surat Pengantar (PDF)
                            </a>
                        </div>

                        <!-- KARTU 2: SURAT BALASAN INSTANSI -->
                        @php
                            $hasSuratBalasan = $pengajuan->documents->where('type', 'SURAT_PENERIMAAN')->first();
                        @endphp
                        <div class="bg-gradient-to-br from-blue-50/60 to-indigo-50/40 p-6 rounded-2xl border border-blue-200/80 flex flex-col justify-between space-y-4 hover:shadow-md transition-all">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="p-2.5 bg-blue-100 text-blue-700 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"></path>
                                        </svg>
                                    </span>
                                    @if($hasSuratBalasan)
                                        <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full bg-emerald-100 text-emerald-800">Sudah Diunggah</span>
                                    @else
                                        <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full bg-blue-200 text-blue-900">Perlu Diunggah</span>
                                    @endif
                                </div>
                                <h5 class="font-bold text-gray-900 text-base">2. Surat Balasan Instansi</h5>
                                <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                                    Unggah bukti surat balasan persetujuan dari instansi agar Admin Prodi dapat <strong>menetapkan DPL</strong>.
                                </p>
                            </div>

                            @if($hasSuratBalasan)
                                <div class="flex items-center justify-between p-3 bg-white/90 rounded-xl border border-emerald-200 text-xs">
                                    <span class="font-bold text-emerald-800 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Berkas Surat Balasan Aktif
                                    </span>
                                    <a href="{{ asset('storage/' . $hasSuratBalasan->file_path) }}" target="_blank" class="text-blue-600 hover:underline font-bold">Lihat File</a>
                                </div>
                            @else
                                <form action="{{ route('mahasiswa.pengajuan.surat_balasan', $pengajuan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                                    @csrf
                                    <div class="flex gap-2">
                                        <input type="file" name="surat_balasan" required accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-900 border border-blue-300 rounded-xl cursor-pointer bg-white file:py-2 file:px-3 file:border-0 file:bg-blue-600 file:text-white file:text-xs file:font-bold hover:file:bg-blue-700">
                                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-sm whitespace-nowrap">
                                            Kirim
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('surat_balasan')" class="text-xs" />
                                </form>
                            @endif
                        </div>

                        <!-- KARTU 3: LAPORAN AKHIR MAGANG & TEMPLATE (TERINTEGRASI) -->
                        @php
                            $hasLaporan = $pengajuan->documents->where('type', 'LAPORAN_AKHIR')->first();
                        @endphp
                        <div class="bg-gradient-to-br from-purple-50/60 to-fuchsia-50/40 p-6 rounded-2xl border border-purple-200/80 flex flex-col justify-between space-y-4 hover:shadow-md transition-all">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="p-2.5 bg-purple-100 text-purple-700 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </span>
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('mahasiswa.laporan.template') }}" class="px-2 py-0.5 text-[10px] font-bold bg-white text-purple-700 border border-purple-200 rounded-lg hover:bg-purple-50 transition shadow-xs flex items-center">
                                            <svg class="w-3 h-3 mr-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Unduh Template
                                        </a>
                                        @if($hasLaporan)
                                            <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-lg bg-purple-200 text-purple-900">Terunggah</span>
                                        @endif
                                    </div>
                                </div>
                                <h5 class="font-bold text-gray-900 text-base">3. Laporan Akhir Magang</h5>
                                <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                                    Unduh template resmi di atas sebagai panduan format, lalu unggah naskah laporan akhir <strong>PDF (Maks. 10MB)</strong> setelah masa magang selesai.
                                </p>
                            </div>

                            @if($hasLaporan)
                                <div class="flex items-center justify-between p-3 bg-white/90 rounded-xl border border-purple-200 text-xs">
                                    <span class="font-bold text-purple-900 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Laporan Akhir Terverifikasi
                                    </span>
                                    <a href="{{ asset('storage/' . $hasLaporan->file_path) }}" target="_blank" class="text-purple-700 hover:underline font-bold">Lihat Laporan</a>
                                </div>
                            @else
                                <form action="{{ route('mahasiswa.pengajuan.laporan_akhir', $pengajuan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                                    @csrf
                                    <div class="flex gap-2">
                                        <input type="file" name="laporan_akhir" required accept=".pdf" class="block w-full text-xs text-gray-900 border border-purple-300 rounded-xl cursor-pointer bg-white file:py-2 file:px-3 file:border-0 file:bg-purple-600 file:text-white file:text-xs file:font-bold hover:file:bg-purple-700">
                                        <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition shadow-sm whitespace-nowrap">
                                            Upload
                                        </button>
                                    </div>
                                    <x-input-error :messages="$errors->get('laporan_akhir')" class="text-xs" />
                                </form>
                            @endif
                        </div>

                    </div>
                </div>

            </div>

            @else

            <!-- 3. FORMULIR PENDAFTARAN BARU DENGAN SLOT-BUILDER INTERAKTIF -->
            <form action="{{ route('mahasiswa.pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{
                isGroup: '1',
                memberCount: 1,
                addMember() {
                    if (this.memberCount < 3) this.memberCount++;
                },
                removeMember() {
                    if (this.memberCount > 1) this.memberCount--;
                }
            }">
                @csrf

                <!-- SYARAT AKADEMIK BANNER / INFOBOX DINAMIS -->
                @if($isAcademicEligible)
                    <div class="bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 border border-emerald-300 rounded-2xl p-5 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-2.5 bg-emerald-600 text-white rounded-xl shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Syarat Akademik Terpenuhi</h4>
                                <p class="text-xs text-emerald-800 mt-0.5">Akun Anda memenuhi syarat pendaftaran magang (Minimal: 100 SKS & IPK 2.50).</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="px-3.5 py-1.5 bg-white rounded-xl border border-emerald-200 text-center shadow-xs">
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">SKS Anda</span>
                                <span class="text-sm font-black text-emerald-700">{{ auth()->user()->total_sks }} SKS</span>
                            </div>
                            <div class="px-3.5 py-1.5 bg-white rounded-xl border border-emerald-200 text-center shadow-xs">
                                <span class="text-[10px] uppercase font-bold text-gray-400 block">IPK Anda</span>
                                <span class="text-sm font-black text-emerald-700">{{ number_format(auth()->user()->ipk, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-amber-500/10 border-2 border-amber-400/80 rounded-2xl p-5 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex items-start space-x-3">
                            <div class="p-2.5 bg-amber-500 text-white rounded-xl shadow-sm flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-bold text-gray-900">Perhatian: Data SKS & IPK Belum Memenuhi Syarat</h4>
                                    <span class="px-2 py-0.5 bg-amber-200 text-amber-900 font-bold text-[10px] rounded-full uppercase tracking-wider">Perlu Diisi</span>
                                </div>
                                <p class="text-xs text-gray-600 mt-1">
                                    @if(empty(auth()->user()->total_sks) || empty(auth()->user()->ipk))
                                        Anda belum melengkapi data <strong>Total SKS</strong> dan <strong>IPK</strong> di profil Anda (Syarat: Min. 100 SKS & IPK 2.50).
                                    @else
                                        SKS/IPK Anda saat ini belum memenuhi batas minimum magang (Min. 100 SKS & IPK 2.50).
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-end">
                            <div class="flex items-center gap-2">
                                <div class="px-3 py-1 bg-white rounded-xl border border-rose-200 text-center shadow-xs">
                                    <span class="text-[9px] uppercase font-bold text-gray-400 block">SKS</span>
                                    <span class="text-xs font-black text-rose-600">{{ auth()->user()->total_sks ?? 0 }} SKS</span>
                                </div>
                                <div class="px-3 py-1 bg-white rounded-xl border border-rose-200 text-center shadow-xs">
                                    <span class="text-[9px] uppercase font-bold text-gray-400 block">IPK</span>
                                    <span class="text-xs font-black text-rose-600">{{ number_format(auth()->user()->ipk ?? 0, 2) }}</span>
                                </div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-sm transition whitespace-nowrap">
                                Lengkapi Profil ➔
                            </a>
                        </div>
                    </div>
                @endif

                <!-- SECTION 1: TIPE PENDAFTARAN & DATA INSTANSI -->
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-5 bg-gradient-to-r from-uin to-uin-hover text-white flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="w-7 h-7 bg-white/20 rounded-full flex items-center justify-center text-xs font-black">1</span>
                            <h3 class="text-base font-bold">Informasi Pendaftaran & Instansi Magang</h3>
                        </div>
                    </div>

                    <div class="p-6 md:p-8 space-y-6">
                        
                        <!-- PILIHAN TIPE PENDAFTARAN: KELOMPOK VS INDIVIDU -->
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-gray-500 mb-3">Tipe Pendaftaran Magang *</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="relative flex cursor-pointer rounded-2xl border p-4 shadow-xs transition-all" :class="isGroup === '1' ? 'border-uin ring-2 ring-uin/20 bg-uin-light/20' : 'border-gray-200 hover:border-gray-300 bg-white'">
                                    <input type="radio" name="is_group" value="1" class="sr-only" x-model="isGroup">
                                    <div class="flex items-start space-x-3">
                                        <div class="p-2 rounded-xl" :class="isGroup === '1' ? 'bg-uin text-white' : 'bg-gray-100 text-gray-500'">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-bold text-gray-900">Magang Kelompok (Hingga 4 Orang)</span>
                                            <span class="mt-0.5 text-xs text-gray-500 leading-relaxed block">Satu perwakilan mendaftarkan data dan berkas seluruh anggota pada instansi yang sama.</span>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative flex cursor-pointer rounded-2xl border p-4 shadow-xs transition-all" :class="isGroup === '0' ? 'border-uin ring-2 ring-uin/20 bg-uin-light/20' : 'border-gray-200 hover:border-gray-300 bg-white'">
                                    <input type="radio" name="is_group" value="0" class="sr-only" x-model="isGroup">
                                    <div class="flex items-start space-x-3">
                                        <div class="p-2 rounded-xl" :class="isGroup === '0' ? 'bg-uin text-white' : 'bg-gray-100 text-gray-500'">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <div>
                                            <span class="block text-sm font-bold text-gray-900">Magang Individu (Mandiri)</span>
                                            <span class="mt-0.5 text-xs text-gray-500 leading-relaxed block">Pendaftaran program magang dilakukan mandiri untuk diri sendiri.</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- PILIHAN JENIS JALUR MAGANG -->
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-gray-500 mb-2">Jalur Penempatan Magang *</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="flex items-center p-3.5 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                                    <input type="radio" name="jenis_magang" value="prodi" class="text-uin focus:ring-uin" checked>
                                    <span class="ml-2.5 text-xs font-bold text-gray-800">Magang Melalui Program Studi (Diarahkan)</span>
                                </label>
                                <label class="flex items-center p-3.5 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-100 transition">
                                    <input type="radio" name="jenis_magang" value="mandiri" class="text-uin focus:ring-uin">
                                    <span class="ml-2.5 text-xs font-bold text-gray-800">Magang Mandiri (Inisiatif Mitra Sendiri)</span>
                                </label>
                            </div>
                        </div>

                        <!-- NAMA & ALAMAT INSTANSI -->
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="instansi" class="block text-xs font-bold text-gray-700 mb-1">Nama Instansi / Perusahaan Tujuan *</label>
                                <input type="text" name="instansi" id="instansi" value="{{ old('instansi') }}" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" required placeholder="Contoh: PT. Bank Mandiri (Persero) Tbk / Dinas Kominfo">
                                <x-input-error :messages="$errors->get('instansi')" class="text-xs mt-1" />
                            </div>

                            <div>
                                <label for="alamat" class="block text-xs font-bold text-gray-700 mb-1">Alamat Lengkap Kantor / Divisi Instansi *</label>
                                <textarea name="alamat" id="alamat" rows="2" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" required placeholder="Tuliskan jalan, nomor, kota/kabupaten lokasi instansi...">{{ old('alamat') }}</textarea>
                                <x-input-error :messages="$errors->get('alamat')" class="text-xs mt-1" />
                            </div>
                        </div>

                        <!-- TANGGAL PELAKSANAAN -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-xs font-bold text-gray-700 mb-1">Tanggal Mulai Magang *</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" required>
                                <x-input-error :messages="$errors->get('start_date')" class="text-xs mt-1" />
                            </div>
                            <div>
                                <label for="end_date" class="block text-xs font-bold text-gray-700 mb-1">Tanggal Selesai Magang *</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5" required>
                                <x-input-error :messages="$errors->get('end_date')" class="text-xs mt-1" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: BUILDER KARTU ANGGOTA KELOMPOK (SLOT BUILDER) -->
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-5 bg-gradient-to-r from-uin to-uin-hover text-white flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="w-7 h-7 bg-white/20 rounded-full flex items-center justify-center text-xs font-black">2</span>
                            <h3 class="text-base font-bold">Data Anggota Kelompok & Berkas Persyaratan</h3>
                        </div>
                        <div x-show="isGroup === '1'" class="flex items-center space-x-2">
                            <button type="button" @click="addMember()" :disabled="memberCount >= 3" class="px-3 py-1 bg-white text-uin rounded-lg text-xs font-bold hover:bg-white/90 disabled:opacity-50 transition shadow-xs">
                                + Tambah Anggota
                            </button>
                            <button type="button" @click="removeMember()" :disabled="memberCount <= 1" class="px-3 py-1 bg-white/20 text-white rounded-lg text-xs font-bold hover:bg-white/30 disabled:opacity-40 transition">
                                - Kurangi
                            </button>
                        </div>
                    </div>

                    <div class="p-6 md:p-8 space-y-6">

                        <!-- KARTU SLOT 1: KETUA KELOMPOK (LOGIN USER) -->
                        <div class="p-5 rounded-2xl border-2 border-uin/30 bg-uin-light/10 space-y-4">
                            <div class="flex items-center justify-between border-b border-uin/20 pb-3">
                                <div class="flex items-center space-x-3">
                                    @if(auth()->user()->avatar_url)
                                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover shadow-sm ring-1 ring-uin/30 flex-shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-uin text-white flex items-center justify-center font-black text-xs shadow-sm flex-shrink-0">
                                            1
                                        </div>
                                    @endif
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</h4>
                                            <span class="px-2 py-0.5 bg-uin text-white rounded text-[10px] font-bold">Ketua Kelompok</span>
                                        </div>
                                        <p class="text-xs text-gray-500 font-mono">NIM: {{ auth()->user()->nim }} | Prodi: {{ auth()->user()->prodi->nama_prodi ?? 'Teknologi Informasi' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-xs space-y-1.5">
                                    <label class="block text-xs font-bold text-gray-700">📄 Unggah Kartu Tanda Mahasiswa (KTM) *</label>
                                    <p class="text-[11px] text-gray-400">Format PDF/JPG/PNG (Maks. 2MB)</p>
                                    <input type="file" name="leader_ktm" required accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-2 file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-bold file:bg-uin-light file:text-uin">
                                    <x-input-error :messages="$errors->get('leader_ktm')" class="text-xs" />
                                </div>

                                <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-xs space-y-1.5">
                                    <label class="block text-xs font-bold text-gray-700">📑 Unggah Transkrip Nilai Akademik *</label>
                                    <p class="text-[11px] text-gray-400">Bukti telah menempuh minimal 100 SKS (Maks. 2MB)</p>
                                    <input type="file" name="leader_transkrip" required accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-2 file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-bold file:bg-uin-light file:text-uin">
                                    <x-input-error :messages="$errors->get('leader_transkrip')" class="text-xs" />
                                </div>
                            </div>
                        </div>

                        <!-- KARTU SLOT 2, 3, 4: ANGGOTA TAMBAHAN (JIKA MODE KELOMPOK) -->
                        <div x-show="isGroup === '1'" class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-black uppercase tracking-wider text-gray-400">Anggota Kelompok Tambahan (Maks. 3 Orang)</h4>
                                <span class="text-xs font-semibold text-uin" x-text="'Total Anggota Terpilih: ' + (memberCount + 1) + ' / 4 Orang'"></span>
                            </div>

                            <template x-for="i in memberCount" :key="i">
                                <div class="p-5 rounded-2xl border border-gray-200 bg-gray-50/70 space-y-4">
                                    <div class="flex items-center justify-between border-gray-200 pb-3 border-b">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center font-bold text-xs" x-text="i + 1"></div>
                                            <span class="text-xs font-bold text-gray-800" x-text="'Slot Anggota ' + (i + 1)"></span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Pilih Mahasiswa Anggota *</label>
                                        <select :name="'member_user_ids[' + (i - 1) + ']'" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5 bg-white">
                                            <option value="">-- Pilih Mahasiswa (Satu Program Studi) --</option>
                                            @foreach($mahasiswaProdi as $mhs)
                                                <option value="{{ $mhs->id }}">{{ $mhs->name }} (NIM: {{ $mhs->nim }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="p-3.5 bg-white rounded-xl border border-gray-200 shadow-xs space-y-1">
                                            <label class="block text-[11px] font-bold text-gray-700">KTM Anggota</label>
                                            <input type="file" :name="'member_ktm[' + (i - 1) + ']'" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-2 file:py-1 file:px-2.5 file:border-0 file:text-[11px] file:font-bold file:bg-gray-100 file:text-gray-700">
                                        </div>

                                        <div class="p-3.5 bg-white rounded-xl border border-gray-200 shadow-xs space-y-1">
                                            <label class="block text-[11px] font-bold text-gray-700">Transkrip Nilai Anggota</label>
                                            <input type="file" :name="'member_transkrip[' + (i - 1) + ']'" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-2 file:py-1 file:px-2.5 file:border-0 file:text-[11px] file:font-bold file:bg-gray-100 file:text-gray-700">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                    </div>

                    <!-- SUBMIT BUTTON BAR -->
                    <div class="p-6 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <span class="text-xs text-gray-500">Pastikan seluruh data dan berkas KTM & Transkrip telah diunggah dengan benar.</span>
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 bg-uin hover:bg-uin-hover text-white text-xs font-bold rounded-xl shadow-md transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            Kirim Pengajuan Magang
                        </button>
                    </div>
                </div>
            </form>
            @endif

        </div>
    </div>
</x-app-layout>