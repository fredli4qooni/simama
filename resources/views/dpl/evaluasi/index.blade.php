<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                Evaluasi & Penilaian Magang
            </h2>
            <p class="text-sm text-gray-500 mt-1">Masukkan nilai dari DPL dan nilai dari Instansi mitra untuk menghitung nilai akhir rata-rata serta cetak PDF.</p>
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
                        <form action="{{ route('dpl.evaluasi.store', $item->id) }}" method="POST">
                            @csrf
                            
                            <div class="p-5 border-b border-gray-100 {{ $item->evaluation ? 'bg-uin-light/30' : 'bg-gray-50/50' }} flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-full {{ $item->evaluation ? 'bg-uin text-white' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center font-bold text-lg shadow-sm">
                                        {{ substr($item->user->name, 0, 1) }}
                                    </div>
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
                                            <span class="font-medium">NIM: {{ $item->user->nim }}</span>
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
                                    
                                    <!-- INPUT NILAI DPL & INSTANSI -->
                                    <div class="lg:col-span-1 space-y-4">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b pb-2">Komponen Penilaian (0 - 100)</h4>
                                        
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">1. Nilai Dosen Pembimbing (DPL) *</label>
                                            <input type="number" step="0.01" name="nilai_dpl" x-model="nilaiDpl" min="0" max="100" class="w-full text-sm border-gray-300 focus:border-uin focus:ring-uin rounded-md shadow-sm" required placeholder="0 - 100">
                                            <p class="text-[11px] text-gray-500 mt-0.5">Berdasarkan bimbingan & logbook.</p>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">2. Nilai dari Instansi Mitra *</label>
                                            <input type="number" step="0.01" name="nilai_instansi" x-model="nilaiInstansi" min="0" max="100" class="w-full text-sm border-gray-300 focus:border-uin focus:ring-uin rounded-md shadow-sm" required placeholder="0 - 100">
                                            <p class="text-[11px] text-gray-500 mt-0.5">Dari pembimbing lapangan di instansi.</p>
                                        </div>

                                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 flex justify-between items-center">
                                            <span class="text-xs font-bold text-gray-700">Kalkulasi Rata-rata:</span>
                                            <span class="text-sm font-black text-uin" x-text="rataRata"></span>
                                        </div>
                                    </div>

                                    <!-- CATATAN EVALUASI & BERKAS LAPORAN -->
                                    <div class="lg:col-span-2 space-y-4 flex flex-col justify-between">
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b pb-2">Catatan Evaluasi / Komentar DPL</h4>
                                            <div class="mt-3">
                                                <textarea name="komentar" rows="4" class="w-full text-sm border-gray-300 focus:border-uin focus:ring-uin rounded-md shadow-sm" placeholder="Berikan catatan evaluasi, saran, dan deskripsi kinerja mahasiswa selama magang...">{{ $item->evaluation->komentar ?? '' }}</textarea>
                                            </div>
                                        </div>

                                        @php
                                            $buktiMandiri = $item->documents->where('type', 'BUKTI_MAGANG_MANDIRI')->first() ?? $item->documents->where('type', 'SURAT_PENERIMAAN')->first();
                                            $laporanAkhir = $item->documents->where('type', 'LAPORAN_AKHIR')->first();
                                        @endphp

                                        @if($item->jenis_magang === 'mandiri')
                                            <div class="p-3.5 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center justify-between">
                                                <div class="flex items-center text-xs text-emerald-900 font-semibold">
                                                    <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Bukti Magang Mandiri: {{ $buktiMandiri ? 'Sudah Diunggah Mahasiswa' : 'Belum Diunggah' }}
                                                </div>
                                                @if($buktiMandiri)
                                                    <a href="{{ asset('storage/' . $buktiMandiri->file_path) }}" target="_blank" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition shadow-xs flex items-center">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        Lihat Bukti Berkas ↗
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <div class="p-3.5 bg-purple-50 rounded-xl border border-purple-200 flex items-center justify-between">
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
                                    {{ $item->evaluation ? 'Perbarui Nilai' : 'Simpan Nilai Magang' }}
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