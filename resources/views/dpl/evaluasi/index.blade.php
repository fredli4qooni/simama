<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                Evaluasi & Penilaian Magang
            </h2>
            <p class="text-sm text-gray-500 mt-1">Masukkan nilai DPL & Instansi mitra, upload berkas penilaian kedua pihak, dan kalkulasi nilai rata-rata otomatis.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="px-4 py-3 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg shadow-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg shadow-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- DOKUMEN & FORMAT ACUAN PENILAIAN DPL -->
            <div class="bg-white shadow-xs rounded-2xl border border-gray-100 p-5">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="p-2.5 bg-uin-light text-uin rounded-xl shadow-2xs flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Format & Dokumen Acuan Evaluasi</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Instrumen dan berkas baku fakultas untuk penilaian instansi mitra, dosen pembimbing, serta pedoman laporan akhir.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- Blanko Nilai Mitra (DOCX & PDF) -->
                        <div class="inline-flex rounded-xl shadow-2xs border border-emerald-300 bg-white overflow-hidden" title="Format form penilaian pembimbing lapangan instansi">
                            <a href="{{ route('mahasiswa.penilaian_instansi.template') }}" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-emerald-800 hover:bg-emerald-50 transition border-r border-emerald-200">
                                <svg class="w-3.5 h-3.5 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Blanko Nilai Mitra (DOCX)
                            </a>
                            <a href="{{ route('mahasiswa.penilaian_instansi.template', ['format' => 'pdf']) }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-50 transition" title="Versi PDF">
                                PDF
                            </a>
                        </div>

                        <!-- Blanko Nilai DPL (PDF) -->
                        <a href="{{ route('mahasiswa.penilaian_dpl.template') }}" target="_blank" class="inline-flex items-center px-3.5 py-1.5 text-xs font-bold text-blue-800 bg-white hover:bg-blue-50 border border-blue-300 rounded-xl transition shadow-2xs" title="Unduh format instrumen penilaian DPL versi PDF">
                            <svg class="w-3.5 h-3.5 mr-1.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Blanko Nilai DPL (PDF)
                        </a>

                        <!-- Panduan & Format Laporan Akhir (DOCX) -->
                        <a href="{{ route('mahasiswa.laporan.template') }}" class="inline-flex items-center px-3.5 py-1.5 text-xs font-bold text-purple-800 bg-white hover:bg-purple-50 border border-purple-300 rounded-xl transition shadow-2xs" title="Unduh panduan KKN Tematik & format baku laporan akhir (DOCX)">
                            <svg class="w-3.5 h-3.5 mr-1.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Panduan Laporan (DOCX)
                        </a>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                @forelse($mahasiswa as $item)
                    <div class="bg-white shadow-sm sm:rounded-xl border {{ $item->evaluation ? 'border-uin/30' : 'border-gray-200' }} overflow-hidden transition-all duration-200 hover:shadow-md" x-data="{
                        nilaiDpl: '{{ $item->evaluation->nilai_dpl ?? '' }}',
                        nilaiInstansi: '{{ $item->evaluation->nilai_instansi ?? '' }}',
                        get rataRata() {
                            let d = parseFloat(this.nilaiDpl) || 0;
                            let i = parseFloat(this.nilaiInstansi) || 0;
                            if (this.nilaiDpl !== '' && this.nilaiInstansi !== '') {
                                return ((d + i) / 2).toFixed(2);
                            }
                            return '-';
                        }
                    }">
                        <form action="{{ route('dpl.evaluasi.store', $item->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="p-5 border-b border-gray-100 {{ $item->evaluation ? 'bg-uin-light/30' : 'bg-gray-50/50' }} flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="flex items-center space-x-4">
                                    @if($item->user->avatar_url)
                                        <img src="{{ $item->user->avatar_url }}" alt="{{ $item->user->name }}" class="w-12 h-12 rounded-full object-cover shadow-sm ring-2 ring-white border border-gray-200 flex-shrink-0">
                                    @else
                                        <div class="w-12 h-12 rounded-full {{ $item->evaluation ? 'bg-uin text-white' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center font-bold text-lg shadow-sm flex-shrink-0">
                                            {{ substr($item->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $item->user->name }}</h3>
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded {{ $item->is_group ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700' }}">
                                                {{ $item->is_group ? 'Kelompok' : 'Individu' }}
                                            </span>
                                            <span class="px-2 py-0.5 text-[10px] font-black rounded uppercase {{ $item->jenis_magang === 'mandiri' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-blue-100 text-blue-800 border border-blue-300' }}">
                                                {{ $item->jenis_magang === 'mandiri' ? 'Magang Mandiri' : 'Magang Prodi' }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500 mt-0.5 flex flex-wrap items-center gap-2">
                                            <span class="font-medium">NPM: {{ $item->user->nim }}</span>
                                            <span class="text-gray-300">|</span>
                                            <span class="flex items-center text-uin font-medium">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                {{ $item->instansi }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    @if($item->evaluation)
                                        <div class="text-right">
                                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nilai Rata-rata</span>
                                            <span class="text-2xl font-black text-uin">{{ number_format($item->evaluation->nilai_akhir, 2) }}</span>
                                        </div>
                                        <a href="{{ route('dpl.evaluasi.cetak', $item->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-uin text-white text-xs font-bold rounded-lg shadow-sm hover:bg-uin-hover transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                            Cetak PDF Penilaian
                                        </a>
                                    @else
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 border border-yellow-200 text-xs font-bold rounded-full shadow-sm">BELUM DINILAI</span>
                                    @endif
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                    
                                    <!-- 1. INPUT NILAI DPL & INSTANSI (KALKULASI RATA-RATA OTOMATIS) -->
                                    <div class="lg:col-span-1 space-y-4">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b pb-2 flex items-center justify-between">
                                            <span>Skor Nilai (0 - 100)</span>
                                            <span class="text-[10px] font-normal text-gray-400">Bobot 50% : 50%</span>
                                        </h4>
                                        
                                        <div>
                                            <div class="flex items-center justify-between mb-1">
                                                <label class="block text-xs font-bold text-gray-700">1. Nilai Dosen Pembimbing (DPL) *</label>
                                                <a href="{{ route('mahasiswa.penilaian_dpl.template') }}" target="_blank" class="text-[11px] text-blue-600 font-semibold hover:underline flex items-center">
                                                    <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                    Blanko DPL (PDF)
                                                </a>
                                            </div>
                                            <input type="number" step="0.01" name="nilai_dpl" x-model="nilaiDpl" min="0" max="100" class="w-full text-sm border-gray-300 focus:border-uin focus:ring-uin rounded-md shadow-sm" required placeholder="0 - 100">
                                            <p class="text-[11px] text-gray-500 mt-0.5">Berdasarkan bimbingan akademis & logbook harian.</p>
                                        </div>

                                        <div>
                                            <div class="flex items-center justify-between mb-1">
                                                <label class="block text-xs font-bold text-gray-700">2. Nilai Pembimbing Instansi *</label>
                                                <div class="flex items-center gap-1.5">
                                                    <a href="{{ route('mahasiswa.penilaian_instansi.template') }}" class="text-[11px] text-emerald-600 font-semibold hover:underline flex items-center">
                                                        DOCX
                                                    </a>
                                                    <span class="text-gray-300">|</span>
                                                    <a href="{{ route('mahasiswa.penilaian_instansi.template', ['format' => 'pdf']) }}" target="_blank" class="text-[11px] text-emerald-600 font-semibold hover:underline flex items-center">
                                                        PDF
                                                    </a>
                                                </div>
                                            </div>
                                            <input type="number" step="0.01" name="nilai_instansi" x-model="nilaiInstansi" min="0" max="100" class="w-full text-sm border-gray-300 focus:border-uin focus:ring-uin rounded-md shadow-sm" required placeholder="0 - 100">
                                            <p class="text-[11px] text-gray-500 mt-0.5">Dari formulir nilai yang diisi pembimbing lapangan instansi.</p>
                                        </div>

                                        <div class="p-3.5 bg-gradient-to-r from-uin-light/40 to-emerald-50 rounded-xl border border-uin/20 flex justify-between items-center shadow-xs">
                                            <div>
                                                <span class="text-xs font-bold text-gray-800 block">Kalkulasi Nilai Akhir:</span>
                                                <span class="text-[10px] text-gray-500">Rata-rata otomatis sistem</span>
                                            </div>
                                            <span class="text-lg font-black text-uin" x-text="rataRata"></span>
                                        </div>
                                    </div>

                                    <!-- 2. UPLOAD BERKAS BUKTI PENILAIAN DPL & INSTANSI (TRANSPARANSI NILAI) -->
                                    <div class="lg:col-span-2 space-y-5">
                                        <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-200 space-y-4">
                                            <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                                                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                                    Upload Berkas Bukti Penilaian (Transparansi Nilai)
                                                </h4>
                                                <span class="text-[11px] text-gray-500">Format: PDF, JPG, PNG (Maks 10MB)</span>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <!-- Berkas Nilai Instansi -->
                                                <div class="bg-white p-3.5 rounded-lg border border-gray-200 space-y-2">
                                                    <div class="flex items-center justify-between">
                                                        <label class="block text-xs font-bold text-gray-800">
                                                            Berkas Nilai Instansi Mitra
                                                        </label>
                                                        @if($item->evaluation && $item->evaluation->berkas_nilai_instansi)
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded">
                                                                <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                                <span>Terunggah</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <p class="text-[11px] text-gray-500">Scan / foto formulir nilai yang telah diisi & ditandatangani pembimbing instansi.</p>
                                                    
                                                    <input type="file" name="berkas_nilai_instansi" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                                                    
                                                    @if($item->evaluation && $item->evaluation->berkas_nilai_instansi)
                                                        <div class="pt-1">
                                                            <a href="{{ route('evaluasi.berkas_instansi', $item->id) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-emerald-600 hover:underline">
                                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                                Buka Berkas Instansi Tersimpan ↗
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Berkas Nilai DPL -->
                                                <div class="bg-white p-3.5 rounded-lg border border-gray-200 space-y-2">
                                                    <div class="flex items-center justify-between">
                                                        <label class="block text-xs font-bold text-gray-800">
                                                            Berkas Nilai DPL / Prodi
                                                        </label>
                                                        @if($item->evaluation && $item->evaluation->berkas_nilai_dpl)
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-bold rounded">
                                                                <svg class="w-3 h-3 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                                <span>Terunggah</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <p class="text-[11px] text-gray-500">Scan / lembar penilaian resmi DPL bertanda tangan sesuai format fakultas.</p>
                                                    
                                                    <input type="file" name="berkas_nilai_dpl" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-xs text-gray-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                                    
                                                    @if($item->evaluation && $item->evaluation->berkas_nilai_dpl)
                                                        <div class="pt-1">
                                                            <a href="{{ route('evaluasi.berkas_dpl', $item->id) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-blue-600 hover:underline">
                                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                                Buka Berkas DPL Tersimpan ↗
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- CATATAN EVALUASI & TINJAUAN MAHASISWA -->
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b pb-2">Catatan Evaluasi / Komentar DPL</h4>
                                            <div class="mt-2">
                                                <textarea name="komentar" rows="3" class="w-full text-sm border-gray-300 focus:border-uin focus:ring-uin rounded-md shadow-sm" placeholder="Berikan catatan evaluasi, saran, dan deskripsi kinerja mahasiswa selama magang...">{{ $item->evaluation->komentar ?? '' }}</textarea>
                                            </div>
                                        </div>

                                        @php
                                            $buktiMandiri = $item->documents->where('type', 'BUKTI_MAGANG_MANDIRI')->first() ?? $item->documents->where('type', 'SURAT_PENERIMAAN')->first();
                                            $laporanAkhir = $item->documents->where('type', 'LAPORAN_AKHIR')->first();
                                        @endphp

                                        @if($item->jenis_magang === 'mandiri')
                                            <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center justify-between">
                                                <div class="flex items-center text-xs text-emerald-900 font-semibold">
                                                    <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Bukti Magang Mandiri: {{ $buktiMandiri ? 'Sudah Diunggah Mahasiswa' : 'Belum Diunggah' }}
                                                </div>
                                                @if($buktiMandiri)
                                                    <a href="{{ asset('storage/' . $buktiMandiri->file_path) }}" target="_blank" class="px-3 py-1 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition shadow-xs flex items-center">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        Lihat Bukti Berkas ↗
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <div class="p-3 bg-purple-50 rounded-xl border border-purple-200 flex items-center justify-between">
                                                <div class="flex items-center text-xs text-purple-900 font-semibold">
                                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Laporan Akhir: {{ $laporanAkhir ? 'Sudah Diunggah Mahasiswa' : 'Belum Diunggah' }}
                                                </div>
                                                @if($laporanAkhir)
                                                    <a href="{{ asset('storage/' . $laporanAkhir->file_path) }}" target="_blank" class="px-2.5 py-1 bg-purple-600 text-white rounded-lg text-xs font-bold hover:bg-purple-700 transition-colors">
                                                        Unduh Laporan ↗
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex justify-end items-center">
                                <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-md text-white bg-uin hover:bg-uin-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uin transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    {{ $item->evaluation ? 'Perbarui Nilai & Berkas' : 'Simpan Nilai & Berkas Magang' }}
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="bg-white rounded-xl shadow-sm border border-dashed border-gray-300 p-12 text-center flex flex-col items-center">
                        <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Mahasiswa Bimbingan</h3>
                        <p class="text-gray-500">Saat ini belum ada mahasiswa magang aktif yang siap untuk dinilai.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>