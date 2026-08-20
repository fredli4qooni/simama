<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard 
            @if(auth()->user()->role === 'admin_fakultas') Admin Fakultas
            @elseif(auth()->user()->role === 'admin') Admin Prodi
            @elseif(auth()->user()->role === 'dpl') Dosen Pembimbing (DPL)
            @else Mahasiswa
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
                    <div class="absolute right-32 -top-10 w-36 h-36 bg-blue-400/20 rounded-full blur-xl pointer-events-none"></div>

                    <div class="relative z-10 flex items-center justify-between gap-6">
                        <div>
                            <h3 class="text-3xl font-bold mb-2 tracking-tight">Selamat Datang, {{ auth()->user()->name }}!</h3>
                            <p class="text-blue-100 text-base md:text-lg font-medium">Sistem Informasi Manajemen Magang (SIMAMA) - UIN Raden Intan Lampung</p>
                            <div class="mt-6 inline-flex items-center px-3.5 py-1.5 text-xs font-bold text-blue-900 bg-white/95 rounded-full shadow-sm">
                                <svg class="w-4 h-4 mr-2 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                            </div>
                        </div>

                        <!-- KONTRAST CARD UNTUK IKON -->
                        <div class="hidden md:flex items-center justify-center w-24 h-24 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border-2 border-white/80 p-3 flex-shrink-0 transform hover:scale-105 transition-all duration-300 ring-4 ring-white/20">
                            <img src="https://img.icons8.com/external-itim2101-blue-itim2101/64/external-books-online-education-itim2101-blue-itim2101.png" alt="E-Education Books" class="w-full h-full object-contain">
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFOBOX JIKA MAHASISWA BELUM MENGISI / BELUM MEMENUHI SKS & IPK -->
            @if(auth()->user()->role === 'mahasiswa' && (empty(auth()->user()->total_sks) || empty(auth()->user()->ipk) || auth()->user()->total_sks < 100 || auth()->user()->ipk < 2.50))
                <div class="bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-amber-500/10 border-2 border-amber-400/80 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 bg-amber-500 text-white rounded-2xl shadow-md flex-shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <h4 class="text-base font-bold text-gray-900">Perhatian: Data SKS & IPK Belum Lengkap</h4>
                                <span class="px-2 py-0.5 bg-amber-200 text-amber-900 font-bold text-[10px] rounded-full uppercase tracking-wider">Wajib Diisi</span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed max-w-2xl">
                                @if(empty(auth()->user()->total_sks) || empty(auth()->user()->ipk))
                                    Anda belum melengkapi data <strong>Total SKS</strong> dan <strong>IPK Kumulatif</strong> di profil akun Anda. Data ini diperlukan untuk memvalidasi kelayakan pendaftaran magang (Syarat: Min. 100 SKS & IPK 2.50).
                                @else
                                    Data akademik Anda saat ini (<strong>{{ auth()->user()->total_sks }} SKS</strong>, <strong>IPK {{ number_format(auth()->user()->ipk, 2) }}</strong>) belum memenuhi batas minimum persyaratan magang (Min. 100 SKS & IPK 2.50).
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

            <!-- Dashboard Mahasiswa -->
            @if(auth()->user()->role === 'mahasiswa')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('mahasiswa.pengajuan.index') }}" class="group bg-white overflow-hidden shadow-sm rounded-xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-100 hover:border-uin border-b-4 border-b-transparent hover:border-b-uin">
                        <div class="text-uin mb-4 bg-uin-light w-14 h-14 flex items-center justify-center rounded-lg group-hover:bg-uin group-hover:text-white transition-colors duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Pengajuan & Berkas</h4>
                        <p class="text-sm text-gray-500">Daftarkan kelompok, unduh surat pengantar, dan unggah berkas balasan.</p>
                    </a>

                    <a href="{{ route('mahasiswa.presensi.index') }}" class="group bg-white overflow-hidden shadow-sm rounded-xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-100 hover:border-uin border-b-4 border-b-transparent hover:border-b-uin">
                        <div class="text-uin mb-4 bg-uin-light w-14 h-14 flex items-center justify-center rounded-lg group-hover:bg-uin group-hover:text-white transition-colors duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Presensi GPS (2x Sehari)</h4>
                        <p class="text-sm text-gray-500">Catat kehadiran Masuk dan Pulang dengan titik koordinat instansi.</p>
                    </a>

                    <a href="{{ route('mahasiswa.logbook.index') }}" class="group bg-white overflow-hidden shadow-sm rounded-xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-100 hover:border-uin border-b-4 border-b-transparent hover:border-b-uin">
                        <div class="text-uin mb-4 bg-uin-light w-14 h-14 flex items-center justify-center rounded-lg group-hover:bg-uin group-hover:text-white transition-colors duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Jurnal Logbook</h4>
                        <p class="text-sm text-gray-500">Tulis kegiatan harian dan dapatkan feedback bimbingan DPL.</p>
                    </a>
                </div>
            @endif

            <!-- Dashboard Dosen Pembimbing (DPL) -->
            @if(auth()->user()->role === 'dpl')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ route('dpl.pemantauan.index') }}" class="group bg-white overflow-hidden shadow-sm rounded-xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-100 hover:border-uin border-b-4 border-b-transparent hover:border-b-uin">
                        <div class="text-uin mb-4 bg-uin-light w-14 h-14 flex items-center justify-center rounded-lg group-hover:bg-uin group-hover:text-white transition-colors duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Pemantauan & Konsultasi</h4>
                        <p class="text-sm text-gray-500">Pantau presensi GPS, periksa logbook harian, dan ruang live chat bimbingan.</p>
                    </a>

                    <a href="{{ route('dpl.evaluasi.index') }}" class="group bg-white overflow-hidden shadow-sm rounded-xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-100 hover:border-uin border-b-4 border-b-transparent hover:border-b-uin">
                        <div class="text-uin mb-4 bg-uin-light w-14 h-14 flex items-center justify-center rounded-lg group-hover:bg-uin group-hover:text-white transition-colors duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Penilaian Magang & Cetak PDF</h4>
                        <p class="text-sm text-gray-500">Input nilai DPL dan nilai Instansi, kalkulasi nilai rata-rata, dan cetak lembar penilaian.</p>
                    </a>
                </div>
            @endif

            <!-- Dashboard Admin Prodi & Admin Fakultas -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'admin_fakultas')
                
                @php
                    $user = auth()->user();
                    $totalProdi = \App\Models\Prodi::count();
                    
                    if($user->role === 'admin_fakultas') {
                        // Global Fakultas
                        $totalMahasiswa = \App\Models\User::where('role', 'mahasiswa')->count();
                        $menungguVerifikasi = \App\Models\MagangApplication::where('status', 'pending')->count();
                        $daftarProdi = \App\Models\Prodi::withCount(['users' => function($q){
                            $q->where('role', 'mahasiswa');
                        }])->get();
                    } else {
                        // Khusus Prodi Sendiri
                        $totalMahasiswa = \App\Models\User::where('role', 'mahasiswa')->where('prodi_id', $user->prodi_id)->count();
                        $menungguVerifikasi = \App\Models\MagangApplication::where('status', 'pending')
                            ->whereHas('user', function($q) use($user) {
                                $q->where('prodi_id', $user->prodi_id);
                            })->count();
                    }
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <p class="text-xs font-bold text-gray-400 uppercase">Total Mahasiswa Magang</p>
                        <p class="text-3xl font-black text-uin mt-1">{{ $totalMahasiswa }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <p class="text-xs font-bold text-gray-400 uppercase">Prodi Terdaftar</p>
                        <p class="text-3xl font-black text-blue-600 mt-1">{{ $totalProdi }} <span class="text-sm font-medium text-gray-400">Prodi</span></p>
                    </div>
                    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <p class="text-xs font-bold text-gray-400 uppercase">Menunggu Verifikasi</p>
                        <p class="text-3xl font-black text-yellow-500 mt-1">{{ $menungguVerifikasi }}</p>
                    </div>
                </div>

                @if(auth()->user()->role === 'admin_fakultas')
                    <div class="bg-white p-6 rounded-xl border border-uin/20 shadow-md mb-6">
                        <h4 class="font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Pemantauan Lintas Program Studi
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($daftarProdi as $prodi)
                                @if($prodi->users_count > 0)
                                    <div class="p-4 bg-uin-light text-uin rounded-lg text-center font-bold border border-uin/20 shadow-sm">
                                        {{ $prodi->nama_prodi }}
                                        <span class="block text-xs font-normal mt-1">{{ $prodi->users_count }} Mhs</span>
                                    </div>
                                @else
                                    <div class="p-4 bg-gray-50 text-gray-400 rounded-lg text-center font-bold border border-dashed border-gray-300">
                                        {{ $prodi->nama_prodi }}
                                        <span class="block text-xs font-normal mt-1">Belum Ada Data</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(auth()->user()->role === 'admin')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <a href="{{ route('admin.verifikasi.index') }}" class="group bg-white overflow-hidden shadow-sm rounded-xl p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-uin">
                            <div class="text-uin mb-4 bg-uin-light w-14 h-14 flex items-center justify-center rounded-lg group-hover:bg-uin group-hover:text-white transition-colors">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">Verifikasi Pengajuan</h4>
                            <p class="text-sm text-gray-500">Tinjau kelayakan berkas KTM & Transkrip kelompok.</p>
                        </a>

                        <a href="{{ route('admin.penetapan_dpl.index') }}" class="group bg-white overflow-hidden shadow-sm rounded-xl p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-uin">
                            <div class="text-uin mb-4 bg-uin-light w-14 h-14 flex items-center justify-center rounded-lg group-hover:bg-uin group-hover:text-white transition-colors">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">Penetapan DPL</h4>
                            <p class="text-sm text-gray-500">Pilih DPL untuk kelompok dan cetak surat tugas.</p>
                        </a>

                        <a href="{{ route('admin.rekap.index') }}" class="group bg-white overflow-hidden shadow-sm rounded-xl p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-uin">
                            <div class="text-uin mb-4 bg-uin-light w-14 h-14 flex items-center justify-center rounded-lg group-hover:bg-uin group-hover:text-white transition-colors">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">Rekap Nilai</h4>
                            <p class="text-sm text-gray-500">Cetak dokumen laporan rekapitulasi akhir.</p>
                        </a>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('admin.users.index') }}" class="group bg-white overflow-hidden shadow-sm rounded-xl p-6 hover:shadow-lg transition-all border border-gray-100 hover:border-uin border-l-4 border-l-uin">
                        <div class="text-uin mb-4 bg-uin-light w-14 h-14 flex items-center justify-center rounded-lg group-hover:bg-uin group-hover:text-white transition-colors">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-1">Manajemen Pengguna</h4>
                        <p class="text-sm text-gray-500">
                            @if(auth()->user()->role === 'admin_fakultas') Buat dan kelola akun Admin Program Studi. @else Kelola akun DPL & Mahasiswa. @endif
                        </p>
                    </a>
                </div>

            @endif

        </div>
    </div>
</x-app-layout>