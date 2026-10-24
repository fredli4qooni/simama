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
                <p class="text-sm text-gray-500 mt-1">Pendaftaran magang prodi & mandiri, administrasi berkas, serta transparansi nilai akhir mahasiswa.</p>
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

            <!-- ALERT ERROR VALIDASI GLOBAL -->
            @if ($errors->any())
            <div class="p-5 bg-rose-50 border-2 border-rose-300 rounded-2xl shadow-sm text-rose-800 text-xs space-y-2.5">
                <div class="flex items-center font-bold text-sm text-rose-900">
                    <div class="p-1.5 bg-rose-200 text-rose-700 rounded-lg mr-2.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <span>Pendaftaran Belum Berhasil. Silakan periksa hal-hal berikut:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 ml-2 text-rose-700 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- 1. JOURNEY STEPPER (TAHAPAN STATUS MAGANG) -->
            @php
                $isAcademicEligible = (!empty(auth()->user()->nim) && auth()->user()->total_sks >= 110 && auth()->user()->ipk >= 2.75);
                $isMandiri = $pengajuan && $pengajuan->jenis_magang === 'mandiri';
            @endphp

            @if($pengajuan)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <h4 class="text-xs font-black tracking-widest text-gray-400 uppercase mb-5 flex items-center">
                        <svg class="w-4 h-4 mr-1.5 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Alur Proses {{ $isMandiri ? 'Magang Mandiri' : 'Magang Program Studi' }}
                    </h4>
                    
                    @if($isMandiri)
                        <!-- STEPPER 3 LANGKAH MAGANG MANDIRI -->
                        @php
                            $mStep = 1;
                            if ($pengajuan->status == 'pending') $mStep = 1;
                            elseif (in_array($pengajuan->status, ['approved', 'active'])) $mStep = 2;
                            elseif ($pengajuan->status == 'selesai' || $pengajuan->evaluation) $mStep = 3;
                        @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="p-4 rounded-xl border {{ $mStep >= 1 ? ($mStep == 1 ? 'bg-uin-light/40 border-uin text-uin' : 'bg-gray-50 border-gray-200 text-gray-800') : 'bg-gray-50 border-gray-100 text-gray-400 opacity-60' }}">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $mStep >= 1 ? 'bg-uin text-white' : 'bg-gray-200 text-gray-600' }}">1</span>
                                    <span class="text-xs font-bold">1. Pendaftaran Mandiri</span>
                                </div>
                                <p class="text-[11px] text-gray-500">Data instansi & berkas akademik terkirim.</p>
                            </div>

                            <div class="p-4 rounded-xl border {{ $mStep >= 2 ? ($mStep == 2 ? 'bg-uin-light/40 border-uin text-uin' : 'bg-gray-50 border-gray-200 text-gray-800') : 'bg-gray-50 border-gray-100 text-gray-400 opacity-60' }}">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $mStep >= 2 ? 'bg-uin text-white' : 'bg-gray-200 text-gray-600' }}">2</span>
                                    <span class="text-xs font-bold">2. Upload Bukti & DPL</span>
                                </div>
                                <p class="text-[11px] text-gray-500">Unggah berkas bukti magang & penetapan DPL.</p>
                            </div>

                            <div class="p-4 rounded-xl border {{ $mStep == 3 ? 'bg-uin-light/40 border-uin text-uin' : 'bg-gray-50 border-gray-100 text-gray-400 opacity-60' }}">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $mStep == 3 ? 'bg-uin text-white' : 'bg-gray-200 text-gray-600' }}">3</span>
                                    <span class="text-xs font-bold">3. Penilaian DPL & Nilai Akhir</span>
                                </div>
                                <p class="text-[11px] text-gray-500">Rata-rata nilai instansi & DPL diterbitkan.</p>
                            </div>
                        </div>
                    @else
                        <!-- STEPPER 5 LANGKAH MAGANG PRODI REGULER -->
                        @php
                            $hasSuratBalasan = $pengajuan->documents->where('type', 'SURAT_PENERIMAAN')->first();
                            $hasLaporan = $pengajuan->documents->where('type', 'LAPORAN_AKHIR')->first();

                            $step = 1;
                            if ($pengajuan->status == 'pending') {
                                $step = 1; // Fase 1: Pendaftaran & Verifikasi Berkas
                            } elseif ($pengajuan->status == 'approved') {
                                if ($hasSuratBalasan) {
                                    $step = 3; // Fase 3: Surat Balasan Terunggah (Menunggu Penetapan DPL)
                                } else {
                                    $step = 2; // Fase 2: Surat Pengantar & Kumpulan Format Unduhan
                                }
                            } elseif ($pengajuan->status == 'active') {
                                if ($hasLaporan || now()->greaterThanOrEqualTo($pengajuan->end_date)) {
                                    $step = 5; // Fase 5: Laporan & Nilai
                                } else {
                                    $step = 4; // Fase 4: Magang Berjalan & DPL
                                }
                            } elseif ($pengajuan->status == 'selesai' || $pengajuan->evaluation) {
                                $step = 5;
                            }
                        @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                            <div class="p-3.5 rounded-xl border {{ $step >= 1 ? ($step == 1 ? 'bg-uin-light/40 border-uin text-uin' : 'bg-gray-50 border-gray-200 text-gray-800') : 'bg-gray-50 border-gray-100 text-gray-400 opacity-60' }}">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $step >= 1 ? 'bg-uin text-white' : 'bg-gray-200 text-gray-600' }}">1</span>
                                    <span class="text-xs font-bold">Pendaftaran</span>
                                </div>
                                <p class="text-[11px] text-gray-500 line-clamp-2">Input berkas kelompok, KTM & Transkrip.</p>
                            </div>

                            <div class="p-3.5 rounded-xl border {{ $step >= 2 ? ($step == 2 ? 'bg-uin-light/40 border-uin text-uin' : 'bg-gray-50 border-gray-200 text-gray-800') : 'bg-gray-50 border-gray-100 text-gray-400 opacity-60' }}">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $step >= 2 ? 'bg-uin text-white' : 'bg-gray-200 text-gray-600' }}">2</span>
                                    <span class="text-xs font-bold">Surat Pengantar</span>
                                </div>
                                <p class="text-[11px] text-gray-500 line-clamp-2">Unduh format surat pengantar & blanko mitra.</p>
                            </div>

                            <div class="p-3.5 rounded-xl border {{ $step >= 3 ? ($step == 3 ? 'bg-uin-light/40 border-uin text-uin' : 'bg-gray-50 border-gray-200 text-gray-800') : 'bg-gray-50 border-gray-100 text-gray-400 opacity-60' }}">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $step >= 3 ? 'bg-uin text-white' : 'bg-gray-200 text-gray-600' }}">3</span>
                                    <span class="text-xs font-bold">Surat Balasan</span>
                                </div>
                                <p class="text-[11px] text-gray-500 line-clamp-2">Unggah bukti penerimaan dari instansi.</p>
                            </div>

                            <div class="p-3.5 rounded-xl border {{ $step >= 4 ? ($step == 4 ? 'bg-uin-light/40 border-uin text-uin' : 'bg-gray-50 border-gray-200 text-gray-800') : 'bg-gray-50 border-gray-100 text-gray-400 opacity-60' }}">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $step >= 4 ? 'bg-uin text-white' : 'bg-gray-200 text-gray-600' }}">4</span>
                                    <span class="text-xs font-bold">Magang & DPL</span>
                                </div>
                                <p class="text-[11px] text-gray-500 line-clamp-2">DPL ditetapkan, presensi GPS & logbook aktif.</p>
                            </div>

                            <div class="p-3.5 rounded-xl border {{ $step == 5 ? 'bg-uin-light/40 border-uin text-uin' : 'bg-gray-50 border-gray-100 text-gray-400 opacity-60' }}">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black {{ $step == 5 ? 'bg-uin text-white' : 'bg-gray-200 text-gray-600' }}">5</span>
                                    <span class="text-xs font-bold">Laporan & Nilai</span>
                                </div>
                                <p class="text-[11px] text-gray-500 line-clamp-2">Unggah laporan akhir & penerimaan nilai.</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- 2. JIKA PENGAJUAN SUDAH ADA (DASHBOARD KELOMPOK / MANDIRI & DOKUMEN HUB) -->
            @if($pengajuan && in_array($pengajuan->status, ['pending', 'approved', 'active', 'selesai']))
            
                <!-- A. HERO STATUS CARD -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 md:p-8 bg-white border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="space-y-2.5">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg tracking-wider uppercase inline-flex items-center gap-1.5 {{ $pengajuan->is_group ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    @if($pengajuan->is_group)
                                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span>Magang Kelompok</span>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span>Magang Individu</span>
                                    @endif
                                </span>
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg tracking-wider uppercase {{ $pengajuan->jenis_magang === 'mandiri' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-uin border border-blue-200' }}">
                                    {{ $pengajuan->jenis_magang === 'mandiri' ? 'MAGANG MANDIRI' : 'MAGANG PRODI' }}
                                </span>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-gray-900 tracking-tight">{{ $pengajuan->instansi }}</h3>
                            <p class="text-xs text-gray-500 flex items-center font-medium">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                {{ $pengajuan->alamat ?? $pengajuan->alamat_instansi }}
                            </p>
                        </div>

                        <div class="flex flex-col items-start md:items-end gap-2 bg-slate-50/80 p-4 rounded-2xl border border-slate-200/80 shadow-2xs">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status Saat Ini</span>
                            @if($pengajuan->status == 'pending')
                                <span class="inline-flex items-center px-3 py-1 bg-amber-50 text-amber-800 rounded-full text-xs font-bold border border-amber-200 shadow-2xs">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 mr-2 animate-pulse"></span> Menunggu Verifikasi Admin Prodi
                                </span>
                            @elseif($pengajuan->status == 'approved')
                                <span class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-800 rounded-full text-xs font-bold border border-blue-200 shadow-2xs">
                                    <span class="w-2 h-2 rounded-full bg-blue-500 mr-2 animate-pulse"></span> Disetujui (Menunggu Penetapan DPL)
                                </span>
                            @elseif($pengajuan->status == 'active')
                                <span class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-800 rounded-full text-xs font-bold border border-emerald-200 shadow-2xs">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-ping"></span> Magang Sedang Berjalan
                                </span>
                            @elseif($pengajuan->status == 'selesai')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-800 rounded-full text-xs font-bold border border-purple-200 shadow-2xs">
                                    <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Selesai & Terverifikasi</span>
                                </span>
                            @endif
                            <span class="text-[11px] text-gray-500 font-mono font-medium mt-0.5 inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>{{ \Carbon\Carbon::parse($pengajuan->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($pengajuan->end_date)->format('d M Y') }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- DAFTAR ANGGOTA (JIKA ADA) ATAU PEMOHON TUNGGAL -->
                    @if($pengajuan->is_group && $pengajuan->members && $pengajuan->members->count() > 1)
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h4 class="text-xs font-black uppercase tracking-wider text-gray-400 mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Susunan Anggota Kelompok
                            </h4>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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
                            </div>
                        </div>
                    @endif
                </div>

                <!-- B. KARTU HASIL EVALUASI & PENILAIAN AKHIR (JIKA NILAI TELAH TERBIT) -->
                @if($pengajuan->evaluation)
                    @php
                        $nilaiAkhir = $pengajuan->evaluation->nilai_akhir;
                        $grade = $nilaiAkhir >= 80 ? 'A' : ($nilaiAkhir >= 70 ? 'B' : ($nilaiAkhir >= 60 ? 'C' : 'D/E'));
                        $predikat = $nilaiAkhir >= 80 ? 'Sangat Baik' : ($nilaiAkhir >= 70 ? 'Baik' : ($nilaiAkhir >= 60 ? 'Cukup' : 'Kurang'));
                        $badgeStyle = $nilaiAkhir >= 80 ? 'bg-emerald-500 text-white' : ($nilaiAkhir >= 70 ? 'bg-blue-600 text-white' : 'bg-amber-500 text-white');
                    @endphp

                    <div class="bg-gradient-to-br from-white via-uin-light/10 to-emerald-50/20 rounded-2xl border-2 border-uin/30 shadow-md p-6 md:p-8 space-y-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-100 pb-5">
                            <div class="flex items-center space-x-3">
                                <div class="p-3 bg-uin text-white rounded-2xl shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-lg font-black text-gray-900">Hasil Evaluasi & Lembar Penilaian Akhir Magang</h3>
                                        <span class="px-2.5 py-0.5 text-[10px] font-black uppercase rounded-full bg-emerald-100 text-emerald-800">Resmi Terbit</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-0.5">Nilai dihitung otomatis dari rata-rata Nilai Instansi Lapangan dan Nilai Dosen Pembimbing.</p>
                                </div>
                            </div>
                            
                            <a href="{{ route('mahasiswa.pengajuan.lembar_nilai', $pengajuan->id) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 bg-uin hover:bg-uin-hover text-white rounded-xl text-xs font-bold shadow-md transition whitespace-nowrap">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Unduh Lembar Penilaian (PDF)
                            </a>
                        </div>

                        <!-- 3 KARTU KOMPONEN NILAI -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs text-center flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block mb-1">1. Nilai Instansi / Mitra (50%)</span>
                                    <span class="text-3xl font-black text-gray-800">{{ number_format($pengajuan->evaluation->nilai_instansi ?? 0, 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-[11px] text-gray-500 mt-2 block">Penilaian kinerja lapangan instansi</span>
                                    @if($pengajuan->evaluation->berkas_nilai_instansi)
                                        <div class="mt-3 pt-2 border-t border-gray-100">
                                            <a href="{{ route('evaluasi.berkas_instansi', $pengajuan->id) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:text-emerald-700 hover:underline">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                Lihat Berkas Nilai Instansi ↗
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-xs text-center flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider block mb-1">2. Nilai Dosen Pembimbing (50%)</span>
                                    <span class="text-3xl font-black text-gray-800">{{ number_format($pengajuan->evaluation->nilai_dpl ?? 0, 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-[11px] text-gray-500 mt-2 block">Penilaian akademis & bimbingan DPL</span>
                                    @if($pengajuan->evaluation->berkas_nilai_dpl)
                                        <div class="mt-3 pt-2 border-t border-gray-100">
                                            <a href="{{ route('evaluasi.berkas_dpl', $pengajuan->id) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                Lihat Berkas Nilai DPL ↗
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-uin-light/50 to-white p-5 rounded-2xl border-2 border-uin text-center flex flex-col justify-between shadow-xs">
                                <div>
                                    <span class="text-[10px] uppercase font-black text-uin tracking-wider block mb-1">Nilai Akhir Rata-rata</span>
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="text-3xl font-black text-uin">{{ number_format($nilaiAkhir, 2) }}</span>
                                        <span class="px-2 py-0.5 rounded-lg text-xs font-black {{ $badgeStyle }}">{{ $grade }}</span>
                                    </div>
                                </div>
                                <span class="text-[11px] font-bold text-uin mt-2 block">Predikat: {{ $predikat }}</span>
                            </div>
                        </div>

                        <!-- CATATAN DPL -->
                        @if($pengajuan->evaluation->komentar)
                            <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-xs flex items-start space-x-3">
                                <div class="p-2 bg-uin-light text-uin rounded-lg flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase text-gray-400 tracking-wider">Catatan Evaluasi DPL:</span>
                                    <p class="text-xs text-gray-800 italic mt-0.5 leading-relaxed">"{{ $pengajuan->evaluation->komentar }}"</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- C. PUSAT BERKAS & DOKUMEN AKSI (DOCUMENT HUB) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 md:p-8 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-wider text-gray-400 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                </svg>
                                Pusat Aksi & Pengelolaan Berkas {{ $isMandiri ? 'Magang Mandiri' : 'Magang Prodi' }}
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">Unduh blanko & panduan resmi atau upload berkas magang Anda di bawah ini.</p>
                        </div>
                        <div class="relative inline-block text-left" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false" type="button" class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-gray-700 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl transition shadow-xs focus:outline-none focus:ring-2 focus:ring-uin/20">
                                <svg class="w-4 h-4 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Format & Dokumen Resmi</span>
                                <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-30 divide-y divide-gray-100" style="display: none;">
                                <div class="px-4 py-2">
                                    <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">Blanko & Panduan Resmi</p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">Format dokumen Program Studi Sistem Informasi</p>
                                </div>
                                <div class="py-1">
                                    <a href="{{ route('mahasiswa.logbook.template_docx') }}" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-sky-50 hover:text-sky-800 transition">
                                        <span class="p-2 rounded-lg bg-sky-100 text-sky-700 mr-3 flex-shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-900 truncate">Format Logbook Kegiatan</p>
                                            <p class="text-[10px] text-gray-400">Microsoft Word (.DOCX)</p>
                                        </div>
                                    </a>
                                    <a href="{{ route('mahasiswa.penilaian_instansi.template') }}" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-emerald-50 hover:text-emerald-800 transition">
                                        <span class="p-2 rounded-lg bg-emerald-100 text-emerald-700 mr-3 flex-shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-900 truncate">Blanko Nilai Pembimbing Mitra</p>
                                            <p class="text-[10px] text-gray-400">Form penilaian instansi (DOCX)</p>
                                        </div>
                                    </a>
                                    <a href="{{ route('mahasiswa.penilaian_dpl.template') }}" target="_blank" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-800 transition">
                                        <span class="p-2 rounded-lg bg-blue-100 text-blue-700 mr-3 flex-shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-900 truncate">Blanko Nilai DPL Magang</p>
                                            <p class="text-[10px] text-gray-400">Lembar penilaian dosen (PDF)</p>
                                        </div>
                                    </a>
                                    <a href="{{ route('mahasiswa.laporan.template') }}" class="flex items-center px-4 py-2 text-xs text-gray-700 hover:bg-purple-50 hover:text-purple-800 transition">
                                        <span class="p-2 rounded-lg bg-purple-100 text-purple-700 mr-3 flex-shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-900 truncate">Panduan & Draf Laporan</p>
                                            <p class="text-[10px] text-gray-400">Sistematika laporan akhir (DOCX)</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($pengajuan->status == 'pending')
                        <div class="p-4 bg-amber-50/90 rounded-2xl border border-amber-200/80 flex items-start space-x-3.5 shadow-xs">
                            <div class="p-2.5 bg-amber-100 text-amber-700 rounded-xl flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <h5 class="text-xs font-black text-amber-900 uppercase tracking-wider">Aksi Dokumen Dinonaktifkan Sementara (Menunggu Verifikasi Admin)</h5>
                                <p class="text-xs text-amber-800 leading-relaxed">
                                    Pendaftaran magang Anda telah berhasil dikirim dan saat ini sedang menunggu verifikasi berkas pendaftaran (KTM, Transkrip Nilai, SPP, & IPK) oleh Admin Program Studi. Selama verifikasi berlangsung, aksi pengunduhan format berkas izin resmi dan pengunggahan berkas tindak lanjut di bawah dinonaktifkan sementara.
                                </p>
                            </div>
                        </div>
                    @endif

                    @php
                        $dpl = $pengajuan->dplAssignment->dpl ?? null;
                    @endphp

                    @if($isMandiri)
                        <!-- ALUR KHUSUS MAGANG MANDIRI: 2 KARTU UTAMA -->
                        @php
                            $buktiMandiri = $pengajuan->documents->where('type', 'BUKTI_MAGANG_MANDIRI')->first() 
                                         ?? $pengajuan->documents->where('type', 'SURAT_PENERIMAAN')->first();
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- KARTU 1: UNGGAH BUKTI MAGANG MANDIRI -->
                            <div class="bg-gradient-to-br from-emerald-50/60 to-teal-50/40 p-6 rounded-2xl border border-emerald-200 flex flex-col justify-between space-y-4 hover:shadow-md transition-all">
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="p-2.5 bg-emerald-100 text-emerald-700 rounded-xl">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </span>
                                        @if($buktiMandiri)
                                            <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full bg-emerald-100 text-emerald-800">Berkas Terunggah</span>
                                        @else
                                            <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full bg-amber-100 text-amber-900">Perlu Diunggah</span>
                                        @endif
                                    </div>
                                    <h5 class="font-bold text-gray-900 text-base">Berkas Bukti Magang Mandiri</h5>
                                    <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                                        Unggah bukti penerimaan, sertifikat, atau lembar nilai mitra (PDF/JPG/PNG maks. 10MB).
                                        Gunakan <a href="{{ route('mahasiswa.penilaian_instansi.template') }}" class="text-emerald-700 font-bold underline hover:text-emerald-800">Blanko Nilai Mitra (DOCX)</a> resmi untuk diisi dan distempel oleh pembimbing instansi.
                                    </p>
                                </div>

                                <div class="space-y-3">
                                    @if($buktiMandiri)
                                        <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-emerald-200 text-xs">
                                            <span class="font-bold text-emerald-800 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Berkas Bukti Aktif
                                            </span>
                                            <a href="{{ asset('storage/' . $buktiMandiri->file_path) }}" target="_blank" class="text-emerald-700 hover:underline font-bold">Lihat Dokumen ↗</a>
                                        </div>
                                    @endif

                                    <form action="{{ route('mahasiswa.pengajuan.bukti_mandiri', $pengajuan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                                        @csrf
                                        <div class="flex gap-2">
                                            <input type="file" name="bukti_magang" required accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-900 border border-emerald-300 rounded-xl cursor-pointer bg-white file:py-2 file:px-3 file:border-0 file:bg-emerald-600 file:text-white file:text-xs file:font-bold hover:file:bg-emerald-700">
                                            <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm whitespace-nowrap">
                                                {{ $buktiMandiri ? 'Perbarui' : 'Unggah' }}
                                            </button>
                                        </div>
                                        <x-input-error :messages="$errors->get('bukti_magang')" class="text-xs" />
                                    </form>
                                </div>
                            </div>

                            <!-- KARTU 2: STATUS PEMBIMBING LAPANGAN (DPL) -->
                            <div class="bg-gradient-to-br from-blue-50/60 to-indigo-50/40 p-6 rounded-2xl border border-blue-200 flex flex-col justify-between space-y-4 hover:shadow-md transition-all">
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="p-2.5 bg-blue-100 text-blue-700 rounded-xl">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </span>
                                        @if($dpl)
                                            <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full bg-blue-100 text-blue-800">DPL Ditetapkan</span>
                                        @else
                                            <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-full bg-gray-200 text-gray-700">Menunggu Penetapan</span>
                                        @endif
                                    </div>
                                    <h5 class="font-bold text-gray-900 text-base">Dosen Pembimbing Lapangan (DPL)</h5>
                                    <p class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                                        DPL bertugas mereview bukti magang Anda dan memberikan nilai akademis yang digabungkan dengan nilai dari instansi.
                                    </p>
                                </div>

                                <div class="p-4 bg-white rounded-xl border border-blue-100 text-xs space-y-2">
                                    @if($dpl)
                                        <div class="flex items-center space-x-3">
                                            @if($dpl->avatar_url)
                                                <img src="{{ $dpl->avatar_url }}" alt="{{ $dpl->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-blue-500/30 flex-shrink-0">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-uin text-white font-bold flex items-center justify-center text-sm shadow-xs flex-shrink-0" style="background-color: #1d4ed8;">
                                                    {{ strtoupper(substr($dpl->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="overflow-hidden min-w-0">
                                                <div class="flex items-center gap-1.5">
                                                    <p class="font-bold text-gray-900 text-xs truncate" title="{{ $dpl->name }}">{{ $dpl->name }}</p>
                                                    <span class="inline-flex items-center text-blue-600 flex-shrink-0" title="Dosen Pembimbing Lapangan Resmi">
                                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                @if(!empty($dpl->nip))
                                                    <p class="text-gray-500 text-[11px] font-mono truncate">NIP. {{ $dpl->nip }}</p>
                                                @elseif(!empty($dpl->nim))
                                                    <p class="text-gray-500 text-[11px] font-mono truncate">NIDN. {{ $dpl->nim }}</p>
                                                @endif
                                                <p class="text-gray-500 text-[10px] truncate flex items-center gap-1 mt-0.5">
                                                    <svg class="w-3 h-3 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                    <span>{{ $dpl->email }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="pt-2 border-t border-gray-100 flex items-center justify-between text-[11px]">
                                            <span class="text-gray-400">Status Penilaian:</span>
                                            <span class="font-bold flex items-center gap-1 {{ $pengajuan->evaluation ? 'text-emerald-600' : 'text-amber-600' }}">
                                                @if($pengajuan->evaluation)
                                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    <span>Nilai Sudah Terbit</span>
                                                @else
                                                    <span>Menunggu Input Nilai</span>
                                                @endif
                                            </span>
                                        </div>
                                    @else
                                        <div class="text-center py-2 text-gray-500">
                                            <p class="font-semibold text-gray-700">DPL Belum Ditetapkan</p>
                                            <p class="text-[11px] text-gray-400 mt-0.5">Admin Program Studi akan segera menetapkan Dosen Pembimbing setelah berkas diverifikasi.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    @else
                        <!-- ALUR LENGKAP MAGANG PROGRAM STUDI: 4 KARTU TAHAPAN -->
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 items-stretch">

                            <!-- KARTU 1: FASE 2 SURAT PENGANTAR & KUMPULAN FORMAT UNDUHAN -->
                            <div class="bg-gradient-to-br from-amber-50/70 via-white to-amber-50/30 p-5 rounded-2xl border border-amber-200/90 flex flex-col justify-between space-y-4 hover:shadow-md transition-all shadow-2xs h-full">
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="p-2 bg-amber-100 text-amber-700 rounded-xl shadow-2xs">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </span>
                                        @if($pengajuan->status == 'pending')
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg bg-amber-100 text-amber-900 border border-amber-200">Fase 2 • Menunggu Verifikasi</span>
                                        @else
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200">Fase 2 • Format Siap Cetak</span>
                                        @endif
                                    </div>
                                    <h5 class="font-bold text-gray-900 text-sm">Fase 2: Surat Pengantar & Format Unduhan</h5>
                                    <p class="text-xs text-gray-600 mt-1.5 leading-relaxed min-h-[50px]">
                                        @if($pengajuan->status == 'pending')
                                            Kumpulan format resmi dapat diunduh setelah berkas pendaftaran Anda disetujui (diverifikasi) oleh Admin Prodi.
                                        @else
                                            Kumpulan format berkas resmi untuk dicetak dan diserahkan ke instansi. Mintakan <strong>tanda tangan manual & stempel</strong> ke pihak Program Studi sebelum diserahkan ke instansi.
                                        @endif
                                    </p>
                                </div>
                                <div class="space-y-2 pt-3 border-t border-amber-200/60">
                                    @if($pengajuan->status == 'pending')
                                        <button type="button" disabled class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 text-gray-400 border border-gray-200 rounded-xl text-xs font-bold cursor-not-allowed" title="Menunggu verifikasi admin prodi">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            Surat Pengantar Belum Terbit
                                        </button>
                                    @else
                                        <a href="{{ route('mahasiswa.izin_magang.template', ['pengajuan_id' => $pengajuan->id]) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-xs transition-colors" title="Unduh surat pengantar perizinan magang resmi otomatis terisi (Word DOCX)">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Unduh Surat Pengantar (DOCX)
                                        </a>
                                    @endif
                                    <a href="{{ route('mahasiswa.penilaian_instansi.template') }}" class="w-full inline-flex items-center justify-center px-3 py-2 bg-white hover:bg-amber-100/60 text-amber-900 border border-amber-300 rounded-xl text-xs font-bold shadow-2xs transition-colors" title="Unduh blanko penilaian untuk pembimbing instansi (Word DOCX)">
                                        <svg class="w-4 h-4 mr-1.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Blanko Nilai Mitra (DOCX)
                                    </a>
                                </div>
                            </div>

                            <!-- KARTU 2: FASE 3 SURAT BALASAN INSTANSI -->
                            @php
                                $hasSuratBalasan = $pengajuan->documents->where('type', 'SURAT_PENERIMAAN')->first();
                            @endphp
                            <div class="bg-gradient-to-br from-blue-50/70 via-white to-blue-50/30 p-5 rounded-2xl border border-blue-200/90 flex flex-col justify-between space-y-4 hover:shadow-md transition-all shadow-2xs h-full">
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="p-2 bg-blue-100 text-blue-700 rounded-xl shadow-2xs">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"></path>
                                            </svg>
                                        </span>
                                        @if($pengajuan->status == 'pending')
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg bg-gray-100 text-gray-700 border border-gray-200">Fase 3 • Terkunci</span>
                                        @elseif($hasSuratBalasan)
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200">Fase 3 • Sudah Diunggah</span>
                                        @else
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg bg-blue-100 text-blue-900 border border-blue-200">Fase 3 • Perlu Diunggah</span>
                                        @endif
                                    </div>
                                    <h5 class="font-bold text-gray-900 text-sm">Fase 3: Surat Balasan Instansi</h5>
                                    <p class="text-xs text-gray-600 mt-1.5 leading-relaxed min-h-[50px]">
                                        @if($pengajuan->status == 'pending')
                                            Formulir unggah surat balasan persetujuan instansi akan dibuka pada <strong>Fase 3</strong> setelah pendaftaran diverifikasi & berkas izin magang (Fase 2) diserahkan ke instansi.
                                        @elseif($hasSuratBalasan)
                                            Surat balasan persetujuan instansi telah berhasil diunggah. Menunggu Admin Program Studi untuk <strong>menetapkan DPL (Fase 4)</strong>.
                                        @else
                                            Setelah menyerahkan berkas izin magang (Fase 2) dan menerima surat persetujuan dari instansi, unggah berkas surat balasan di sini agar Admin dapat <strong>menetapkan DPL</strong>.
                                        @endif
                                    </p>
                                </div>

                                <div class="space-y-2 pt-3 border-t border-blue-200/60">
                                    @if($pengajuan->status == 'pending')
                                        <div class="p-3 bg-gray-100/80 rounded-xl border border-dashed border-gray-300 text-xs text-gray-500 space-y-1">
                                            <div class="flex items-center text-gray-700 font-bold gap-1.5">
                                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                <span>Upload Belum Dibuka</span>
                                            </div>
                                            <p class="text-[11px] text-gray-400 leading-relaxed">
                                                Formulir ini aktif setelah pendaftaran diverifikasi & disetujui Admin.
                                            </p>
                                        </div>
                                    @elseif($hasSuratBalasan)
                                        <div class="p-3 bg-white rounded-xl border border-blue-200/80 shadow-2xs space-y-2">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Berkas Surat Balasan Aktif
                                                </span>
                                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">Terunggah</span>
                                            </div>
                                            <a href="{{ asset('storage/' . $hasSuratBalasan->file_path) }}" target="_blank" class="w-full inline-flex items-center justify-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-bold transition">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Lihat File ↗
                                            </a>
                                        </div>
                                    @else
                                        <form action="{{ route('mahasiswa.pengajuan.surat_balasan', $pengajuan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                                            @csrf
                                            <div class="flex flex-col gap-1.5">
                                                <input type="file" name="surat_balasan" required accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-900 border border-blue-300 rounded-xl cursor-pointer bg-white file:py-2 file:px-3 file:border-0 file:bg-blue-600 file:text-white file:text-xs file:font-bold hover:file:bg-blue-700">
                                                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
                                                    Kirim Surat Balasan
                                                </button>
                                            </div>
                                            <x-input-error :messages="$errors->get('surat_balasan')" class="text-xs" />
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- KARTU 3: FASE 4 STATUS DOSEN PEMBIMBING LAPANGAN (DPL) -->
                            <div class="bg-gradient-to-br from-teal-50/70 via-white to-teal-50/30 p-5 rounded-2xl border border-teal-200/90 flex flex-col justify-between space-y-4 hover:shadow-md transition-all shadow-2xs h-full">
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="p-2 bg-teal-100 text-teal-700 rounded-xl shadow-2xs">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </span>
                                        @if($dpl)
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200">Fase 4 • DPL Ditetapkan</span>
                                        @elseif($pengajuan->status == 'pending')
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg bg-gray-100 text-gray-700 border border-gray-200">Fase 4 • Terkunci</span>
                                        @else
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg bg-amber-100 text-amber-800 border border-amber-200">Fase 4 • Menunggu DPL</span>
                                        @endif
                                    </div>
                                    <h5 class="font-bold text-gray-900 text-sm">Fase 4: Pembimbing (DPL)</h5>
                                    <p class="text-xs text-gray-600 mt-1.5 leading-relaxed min-h-[50px]">
                                        @if($dpl)
                                            DPL bertugas memantau kegiatan presensi GPS & logbook, serta memberikan nilai bimbingan akademis.
                                        @elseif($hasSuratBalasan)
                                            Surat balasan instansi telah diunggah. Menunggu Admin Program Studi menetapkan DPL untuk kelompok/pendaftaran Anda.
                                        @else
                                            Dosen Pembimbing Lapangan akan ditetapkan oleh Admin Prodi setelah berkas Fase 3 diunggah.
                                        @endif
                                    </p>
                                </div>

                                <div class="space-y-2 pt-3 border-t border-teal-200/60">
                                    @if($dpl)
                                        <div class="p-3 bg-white rounded-xl border border-teal-200/80 shadow-2xs space-y-2.5">
                                            <div class="flex items-center space-x-3">
                                                @if($dpl->avatar_url)
                                                    <img src="{{ $dpl->avatar_url }}" alt="{{ $dpl->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-teal-500/30 flex-shrink-0">
                                                @else
                                                    <div class="w-10 h-10 rounded-full text-white font-bold flex items-center justify-center text-sm shadow-xs flex-shrink-0 ring-2 ring-teal-500/20" style="background-color: #0f766e;">
                                                        {{ strtoupper(substr($dpl->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div class="overflow-hidden min-w-0">
                                                    <div class="flex items-center gap-1.5">
                                                        <p class="font-bold text-gray-900 text-xs truncate" title="{{ $dpl->name }}">{{ $dpl->name }}</p>
                                                        <span class="inline-flex items-center text-teal-600 flex-shrink-0" title="Dosen Pembimbing Lapangan Resmi">
                                                            <svg class="w-3.5 h-3.5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    @if(!empty($dpl->nip))
                                                        <p class="text-gray-500 text-[11px] font-mono truncate">NIP. {{ $dpl->nip }}</p>
                                                    @elseif(!empty($dpl->nim))
                                                        <p class="text-gray-500 text-[11px] font-mono truncate">NIDN. {{ $dpl->nim }}</p>
                                                    @endif
                                                    <p class="text-gray-500 text-[10px] truncate flex items-center gap-1 mt-0.5">
                                                        <svg class="w-3 h-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                        <span>{{ $dpl->email }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="pt-2 border-t border-gray-100 flex items-center justify-between text-[11px]">
                                                <span class="text-gray-400">Status DPL:</span>
                                                <span class="font-bold text-emerald-700 flex items-center bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                                    Bimbingan Aktif
                                                </span>
                                            </div>
                                        </div>
                                    @elseif($pengajuan->status == 'pending')
                                        <div class="p-3 bg-gray-100/80 rounded-xl border border-dashed border-gray-300 text-xs text-gray-500 space-y-1">
                                            <div class="flex items-center text-gray-700 font-bold gap-1.5">
                                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                <span>Fase Belum Aktif</span>
                                            </div>
                                            <p class="text-[11px] text-gray-400 leading-relaxed">
                                                Menunggu verifikasi berkas pendaftaran selesai.
                                            </p>
                                        </div>
                                    @else
                                        <div class="p-3 bg-teal-50/80 rounded-xl border border-dashed border-teal-300 text-xs text-teal-900 space-y-1">
                                            <div class="flex items-center text-teal-900 font-bold gap-1.5">
                                                <svg class="w-4 h-4 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>Menunggu Penetapan Admin</span>
                                            </div>
                                            <p class="text-[11px] text-teal-700 leading-relaxed">
                                                Admin Prodi akan segera menetapkan DPL untuk pengajuan Anda.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- KARTU 4: FASE 5 LAPORAN AKHIR MAGANG -->
                            @php
                                $hasLaporan = $pengajuan->documents->where('type', 'LAPORAN_AKHIR')->first();
                                $isFaseLaporan = in_array($pengajuan->status, ['active', 'selesai']);
                            @endphp
                            <div class="bg-gradient-to-br from-purple-50/70 via-white to-purple-50/30 p-5 rounded-2xl border border-purple-200/90 flex flex-col justify-between space-y-4 hover:shadow-md transition-all shadow-2xs h-full">
                                <div>
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="p-2 bg-purple-100 text-purple-700 rounded-xl shadow-2xs">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </span>
                                        @if($hasLaporan)
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200">Fase 5 • Terunggah</span>
                                        @elseif($isFaseLaporan)
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg bg-purple-100 text-purple-900 border border-purple-200">Fase 5 • Perlu Diunggah</span>
                                        @else
                                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg bg-gray-100 text-gray-700 border border-gray-200">Fase 5 • Terkunci</span>
                                        @endif
                                    </div>
                                    <h5 class="font-bold text-gray-900 text-sm">Fase 5: Laporan Akhir Magang</h5>
                                    <p class="text-xs text-gray-600 mt-1.5 leading-relaxed min-h-[50px]">
                                        @if($isFaseLaporan)
                                            Unggah naskah laporan akhir <strong>PDF (Maks. 10MB)</strong> yang telah disetujui DPL dan pembimbing lapangan.
                                        @else
                                            Naskah laporan akhir diserahkan pada <strong>Fase 5 (Laporan & Nilai)</strong> setelah kegiatan magang aktif selesai dilaksanakan.
                                        @endif
                                    </p>
                                </div>

                                <div class="space-y-2 pt-3 border-t border-purple-200/60">
                                    @if($hasLaporan)
                                        <div class="p-3 bg-white rounded-xl border border-purple-200/80 shadow-2xs space-y-2">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Laporan Akhir Terverifikasi
                                                </span>
                                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">Terunggah</span>
                                            </div>
                                            <a href="{{ asset('storage/' . $hasLaporan->file_path) }}" target="_blank" class="w-full inline-flex items-center justify-center px-3 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg text-xs font-bold transition">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                Lihat File ↗
                                            </a>
                                        </div>
                                    @elseif($isFaseLaporan)
                                        <form action="{{ route('mahasiswa.pengajuan.laporan_akhir', $pengajuan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                                            @csrf
                                            <div class="flex flex-col gap-1.5">
                                                <input type="file" name="laporan_akhir" required accept=".pdf" class="block w-full text-xs text-gray-900 border border-purple-300 rounded-xl cursor-pointer bg-white file:py-2 file:px-3 file:border-0 file:bg-purple-600 file:text-white file:text-xs file:font-bold hover:file:bg-purple-700">
                                                <button type="submit" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
                                                    Upload Laporan Akhir
                                                </button>
                                            </div>
                                            <x-input-error :messages="$errors->get('laporan_akhir')" class="text-xs" />
                                        </form>
                                    @else
                                        <div class="space-y-2">
                                            <div class="p-3 bg-purple-50/80 rounded-xl border border-dashed border-purple-200 text-xs text-purple-900 space-y-1">
                                                <div class="flex items-center font-bold gap-1.5 text-purple-900">
                                                    <svg class="w-4 h-4 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                    </svg>
                                                    <span>Upload Terkunci (Fase 5)</span>
                                                </div>
                                                <p class="text-[11px] text-purple-700 leading-relaxed">
                                                    Formulir unggah laporan akan terbuka otomatis setelah periode magang berjalan (Fase 5).
                                                </p>
                                            </div>
                                            <a href="{{ route('mahasiswa.laporan.template') }}" class="w-full inline-flex items-center justify-center px-3 py-2 bg-white hover:bg-purple-50 text-purple-800 border border-purple-300 rounded-xl text-xs font-bold shadow-2xs transition" title="Unduh format panduan laporan magang resmi (DOCX)">
                                                <svg class="w-4 h-4 mr-1.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                Unduh Panduan & Draf Laporan (DOCX)
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    @endif
                </div>

            @else

            <!-- 3. FORMULIR PENDAFTARAN BARU DENGAN SMART BUILDER -->
            <form action="{{ route('mahasiswa.pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{
                jenisMagang: 'prodi',
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
                                <p class="text-xs text-emerald-800 mt-0.5">Akun Anda memenuhi syarat pendaftaran magang (Minimal: 110 SKS & IPK 2.75).</p>
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
                                    <h4 class="text-sm font-bold text-gray-900">Perhatian: Lengkapi NIM/NPM & Syarat Akademik</h4>
                                    <span class="px-2 py-0.5 bg-amber-200 text-amber-900 font-bold text-[10px] rounded-full uppercase tracking-wider">Perlu Diisi</span>
                                </div>
                                <p class="text-xs text-gray-600 mt-1">
                                    @if(empty(auth()->user()->nim) || empty(auth()->user()->total_sks) || empty(auth()->user()->ipk))
                                        Anda belum melengkapi data <strong>NIM/NPM</strong>, <strong>Total SKS</strong>, atau <strong>IPK</strong> di profil Anda (Syarat: Min. 110 SKS (setara semester 6 ke atas) & IPK 2.75).
                                    @else
                                        SKS/IPK Anda saat ini belum memenuhi batas minimum magang (Min. 110 SKS & IPK 2.75).
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
                            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold shadow-sm transition whitespace-nowrap inline-flex items-center gap-1.5">
                                <span>Lengkapi Profil</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                @endif

                <!-- DOKUMEN & TEMPLATE RESMI PERSIAPAN MAGANG -->
                <div class="bg-gradient-to-r from-slate-50 via-blue-50/40 to-slate-50 border border-slate-200 rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="flex items-center space-x-3">
                        <div class="p-2.5 bg-uin-light text-uin rounded-xl flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h5 class="text-xs font-bold text-gray-900">Format Dokumen Resmi dari Fakultas & Akademik</h5>
                            <p class="text-[11px] text-gray-500">Unduh format surat izin atau format logbook resmi kegiatan magang.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto">
                        <a href="{{ route('mahasiswa.izin_magang.template') }}" class="px-3 py-1.5 text-xs font-bold text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-300 rounded-xl transition shadow-xs flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Format Izin Magang (DOCX)
                        </a>
                        <a href="{{ route('mahasiswa.logbook.template_docx') }}" class="px-3 py-1.5 text-xs font-bold text-sky-800 bg-sky-50 hover:bg-sky-100 border border-sky-300 rounded-xl transition shadow-xs flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Format Logbook (DOCX)
                        </a>
                    </div>
                </div>

                <!-- SECTION 1: JALUR PENEMPATAN & DATA INSTANSI -->
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-5 bg-gradient-to-r from-uin to-uin-hover text-white flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="w-7 h-7 bg-white/20 rounded-full flex items-center justify-center text-xs font-black">1</span>
                            <h3 class="text-base font-bold">Informasi Pendaftaran & Instansi Magang</h3>
                        </div>
                    </div>

                    <div class="p-6 md:p-8 space-y-6">
                        
                        <!-- PILIHAN JENIS JALUR MAGANG -->
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-gray-500 mb-2">Pilih Jalur Magang *</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="flex items-center p-4 rounded-2xl border cursor-pointer transition-all" :class="jenisMagang === 'prodi' ? 'border-uin ring-2 ring-uin/20 bg-uin-light/20' : 'border-gray-200 hover:border-gray-300 bg-white'">
                                    <input type="radio" name="jenis_magang" value="prodi" class="text-uin focus:ring-uin" x-model="jenisMagang" @change="isGroup = '1'">
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-gray-900">Magang Program Studi (Reguler)</span>
                                        <span class="text-xs text-gray-500 block mt-0.5">Diarahkan oleh program studi, mendukung pendaftaran kelompok/individu dan surat pengantar resmi.</span>
                                    </div>
                                </label>
                                <label class="flex items-center p-4 rounded-2xl border cursor-pointer transition-all" :class="jenisMagang === 'mandiri' ? 'border-uin ring-2 ring-uin/20 bg-uin-light/20' : 'border-gray-200 hover:border-gray-300 bg-white'">
                                    <input type="radio" name="jenis_magang" value="mandiri" class="text-uin focus:ring-uin" x-model="jenisMagang" @change="isGroup = '0'">
                                    <div class="ml-3">
                                        <span class="block text-sm font-bold text-gray-900">Magang Mandiri (Inisiatif / MSIB)</span>
                                        <span class="text-xs text-gray-500 block mt-0.5">Inisiatif sendiri ke mitra atau program MSIB. Cukup unggah bukti magang mandiri dan dinilai DPL.</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- PILIHAN TIPE PENDAFTARAN: KELOMPOK VS INDIVIDU (KHUSUS PRODI) -->
                        <div x-show="jenisMagang === 'prodi'" class="transition-all">
                            <label class="block text-xs font-black uppercase tracking-wider text-gray-500 mb-3">Tipe Pendaftaran Magang Prodi *</label>
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
                                            <span class="block text-sm font-bold text-gray-900">Magang Individu (Mandiri Sendiri)</span>
                                            <span class="mt-0.5 text-xs text-gray-500 leading-relaxed block">Pendaftaran program magang dilakukan mandiri untuk diri sendiri.</span>
                                        </div>
                                    </div>
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

                <!-- SECTION 2: BERKAS PERSYARATAN & ANGGOTA (SLOT BUILDER) -->
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-5 bg-gradient-to-r from-uin to-uin-hover text-white flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="w-7 h-7 bg-white/20 rounded-full flex items-center justify-center text-xs font-black">2</span>
                            <h3 class="text-base font-bold" x-text="jenisMagang === 'mandiri' ? 'Berkas Persyaratan Magang Mandiri' : 'Data Anggota & Berkas Persyaratan'"></h3>
                        </div>
                        <div x-show="jenisMagang === 'prodi' && isGroup === '1'" class="flex items-center space-x-2">
                            <button type="button" @click="addMember()" :disabled="memberCount >= 3" class="px-3 py-1 bg-white text-uin rounded-lg text-xs font-bold hover:bg-white/90 disabled:opacity-50 transition shadow-xs">
                                + Tambah Anggota
                            </button>
                            <button type="button" @click="removeMember()" :disabled="memberCount <= 1" class="px-3 py-1 bg-white/20 text-white rounded-lg text-xs font-bold hover:bg-white/30 disabled:opacity-40 transition">
                                - Kurangi
                            </button>
                        </div>
                    </div>

                    <div class="p-6 md:p-8 space-y-6">

                        <!-- KARTU SLOT 1: PEMOHON / KETUA KELOMPOK (LOGIN USER) -->
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
                                            <span class="px-2 py-0.5 bg-uin text-white rounded text-[10px] font-bold" x-text="jenisMagang === 'mandiri' ? 'Pemohon Mandiri' : (isGroup === '1' ? 'Ketua Kelompok' : 'Pemohon Tunggal')"></span>
                                        </div>
                                        <p class="text-xs text-gray-500 font-mono">NIM: {{ auth()->user()->nim }} | Prodi: {{ auth()->user()->prodi->nama_prodi ?? 'Sistem Informasi' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-xs space-y-1.5">
                                    <label class="flex items-center gap-1.5 text-xs font-bold text-gray-700">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                        <span>Unggah Kartu Tanda Mahasiswa (KTM) *</span>
                                    </label>
                                    <p class="text-[11px] text-gray-400">Format PDF/JPG/PNG (Maks. 5MB)</p>
                                    <input type="file" name="leader_ktm" required accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-2 file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-bold file:bg-uin-light file:text-uin">
                                    <x-input-error :messages="$errors->get('leader_ktm')" class="text-xs" />
                                </div>

                                <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-xs space-y-1.5">
                                    <label class="flex items-center gap-1.5 text-xs font-bold text-gray-700">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Unggah Transkrip Nilai Akademik *</span>
                                    </label>
                                    <p class="text-[11px] text-gray-400">Min. 110 SKS & IPK 2.75 (Maks. 5MB)</p>
                                    <input type="file" name="leader_transkrip" required accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-2 file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-bold file:bg-uin-light file:text-uin">
                                    <x-input-error :messages="$errors->get('leader_transkrip')" class="text-xs" />
                                </div>

                                <div class="p-4 bg-white rounded-xl border border-gray-200 shadow-xs space-y-1.5">
                                    <label class="flex items-center gap-1.5 text-xs font-bold text-gray-700">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        <span>Slip Pembayaran SPP / UKT *</span>
                                    </label>
                                    <p class="text-[11px] text-gray-400">Bukti lunas semester aktif (Maks. 5MB)</p>
                                    <input type="file" name="leader_spp" required accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-2 file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-bold file:bg-uin-light file:text-uin">
                                    <x-input-error :messages="$errors->get('leader_spp')" class="text-xs" />
                                </div>
                            </div>

                            <!-- KHUSUS MANDIRI: OPSI UPLOAD BUKTI LANGSUNG SAAT DAFTAR -->
                            <div x-show="jenisMagang === 'mandiri'" class="p-4 bg-white rounded-xl border-2 border-dashed border-emerald-300 space-y-1.5 mt-2">
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center gap-1.5 text-xs font-bold text-emerald-800">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                        <span>Berkas Bukti Magang Mandiri / Surat Penerimaan (Opsional Saat Ini)</span>
                                    </label>
                                    <span class="text-[10px] text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded">Maks. 10MB</span>
                                </div>
                                <p class="text-[11px] text-gray-500">Anda dapat melampirkannya sekarang, atau mengunggahnya nanti di halaman dashboard setelah pendaftaran.</p>
                                <input type="file" name="bukti_magang_mandiri" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-700 border border-emerald-200 rounded-lg cursor-pointer bg-emerald-50/50 file:mr-2 file:py-1.5 file:px-3 file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white">
                                <x-input-error :messages="$errors->get('bukti_magang_mandiri')" class="text-xs" />
                            </div>
                        </div>

                        <!-- KARTU SLOT ANGGOTA TAMBAHAN (HANYA MUNCUL DI JALUR PRODI KELOMPOK) -->
                        <div x-show="jenisMagang === 'prodi' && isGroup === '1'" class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xs font-black uppercase tracking-wider text-gray-400">Anggota Kelompok Tambahan (Maks. 3 Orang)</h4>
                                <span class="text-xs font-semibold text-uin" x-text="'Total Anggota: ' + (memberCount + 1) + ' / 4 Orang'"></span>
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
                                        <select :name="'member_user_ids[' + (i - 1) + ']'" :disabled="jenisMagang !== 'prodi' || isGroup !== '1'" class="w-full text-xs rounded-xl border-gray-300 shadow-xs focus:border-uin focus:ring-uin p-2.5 bg-white">
                                            <option value="">-- Pilih Mahasiswa (Sistem Informasi) --</option>
                                            @foreach($mahasiswaProdi as $mhs)
                                                <option value="{{ $mhs->id }}">{{ $mhs->name }} (NIM: {{ $mhs->nim }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="p-3.5 bg-white rounded-xl border border-gray-200 shadow-xs space-y-1">
                                            <label class="block text-[11px] font-bold text-gray-700">KTM Anggota</label>
                                            <input type="file" :name="'member_ktm[' + (i - 1) + ']'" :disabled="jenisMagang !== 'prodi' || isGroup !== '1'" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-2 file:py-1 file:px-2.5 file:border-0 file:text-[11px] file:font-bold file:bg-gray-100 file:text-gray-700">
                                        </div>

                                        <div class="p-3.5 bg-white rounded-xl border border-gray-200 shadow-xs space-y-1">
                                            <label class="block text-[11px] font-bold text-gray-700">Transkrip Nilai Anggota</label>
                                            <input type="file" :name="'member_transkrip[' + (i - 1) + ']'" :disabled="jenisMagang !== 'prodi' || isGroup !== '1'" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-2 file:py-1 file:px-2.5 file:border-0 file:text-[11px] file:font-bold file:bg-gray-100 file:text-gray-700">
                                        </div>

                                        <div class="p-3.5 bg-white rounded-xl border border-gray-200 shadow-xs space-y-1">
                                            <label class="block text-[11px] font-bold text-gray-700">Slip SPP Anggota</label>
                                            <input type="file" :name="'member_spp[' + (i - 1) + ']'" :disabled="jenisMagang !== 'prodi' || isGroup !== '1'" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 file:mr-2 file:py-1 file:px-2.5 file:border-0 file:text-[11px] file:font-bold file:bg-gray-100 file:text-gray-700">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                    </div>

                    <!-- SUBMIT BUTTON BAR -->
                    <div class="p-6 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <span class="text-xs text-gray-500">Pastikan seluruh data dan berkas persyaratan telah diisi dengan benar.</span>
                        @if($isAcademicEligible)
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 bg-uin hover:bg-uin-hover text-white text-xs font-bold rounded-xl shadow-md transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                Kirim Pendaftaran Magang
                            </button>
                        @else
                            <a href="{{ route('profile.edit') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-md transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Lengkapi Profil (NIM, SKS, IPK) Terlebih Dahulu
                            </a>
                        @endif
                    </div>
                </div>
            </form>
            @endif

        </div>
    </div>
</x-app-layout>