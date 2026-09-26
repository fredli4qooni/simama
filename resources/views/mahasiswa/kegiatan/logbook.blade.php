<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight flex items-center gap-2">
                <span class="p-2 bg-uin-light text-uin rounded-lg shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </span>
                Jurnal Logbook Kegiatan
            </h2>
            <p class="text-sm text-gray-500 mt-1">Catat aktivitas harian dan tinjau catatan feedback serta review dari Dosen Pembimbing Lapangan Anda.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="px-4 py-3 text-sm text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl shadow-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="px-4 py-3 text-sm text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- 1. FORM INPUT LOGBOOK BARU -->
            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-uin to-uin-hover text-white flex items-center justify-between">
                    <h3 class="text-base font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Tulis Jurnal Kegiatan Harian Baru
                    </h3>
                    <span class="text-xs bg-white/20 px-3 py-1 rounded-full font-medium">Wajib diisi berkala</span>
                </div>
                <div class="p-6 md:p-8">
                    <form action="{{ route('mahasiswa.logbook.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="max-w-xs">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Tanggal Kegiatan *</label>
                            <input class="block w-full border-gray-300 focus:border-uin focus:ring-uin rounded-xl shadow-xs text-xs p-2.5" type="date" name="date" value="{{ old('date', \Carbon\Carbon::today()->format('Y-m-d')) }}" required max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" />
                            <x-input-error :messages="$errors->get('date')" class="text-xs mt-1" />
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Uraian Aktivitas & Kegiatan *</label>
                                <textarea name="kegiatan" rows="4" class="block w-full border-gray-300 focus:border-uin focus:ring-uin rounded-xl shadow-xs text-xs p-3 leading-relaxed" required placeholder="Deskripsikan pekerjaan, proyek, atau penugasan yang Anda kerjakan pada hari tersebut...">{{ old('kegiatan') }}</textarea>
                                <x-input-error :messages="$errors->get('kegiatan')" class="text-xs mt-1" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Hasil / Luaran (Output) *</label>
                                <textarea name="output" rows="4" class="block w-full border-gray-300 focus:border-uin focus:ring-uin rounded-xl shadow-xs text-xs p-3 leading-relaxed" required placeholder="Contoh: Modul autentikasi selesai, dokumentasi ERD diserahkan, 20 data terinput...">{{ old('output') }}</textarea>
                                <x-input-error :messages="$errors->get('output')" class="text-xs mt-1" />
                            </div>
                        </div>

                        <div class="flex justify-end pt-2 border-t border-gray-100">
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-uin hover:bg-uin-hover text-white rounded-xl text-xs font-bold shadow-sm transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Jurnal Logbook
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 2. RIWAYAT LOGBOOK MAHASISWA -->
            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50/80 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Riwayat Jurnal Logbook & Catatan Feedback DPL
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Daftar rekaman kegiatan yang telah Anda simpan beserta ulasan dari Dosen Pembimbing.</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 bg-gray-200/80 text-gray-700 rounded-full">
                        Total: {{ $logbooks->count() }} Catatan
                    </span>
                </div>

                <div class="p-6 divide-y divide-gray-100 space-y-4">
                    @forelse($logbooks as $log)
                        <div class="pt-4 first:pt-0">
                            <div class="border border-gray-200 rounded-2xl bg-white shadow-xs overflow-hidden transition-all hover:border-uin/40 hover:shadow-sm">
                                <div class="bg-gray-50/80 px-5 py-3 border-b border-gray-100 flex flex-wrap justify-between items-center gap-2">
                                    <span class="text-xs font-bold text-gray-900 flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1.5 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($log->date)->translatedFormat('l, d F Y') }}
                                    </span>
                                    <span class="text-[11px] text-gray-400 font-mono">Dicatat: {{ $log->created_at->format('H:i') }} WIB</span>
                                </div>
                                
                                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Aktivitas Kegiatan:</h4>
                                        <p class="text-xs text-gray-800 leading-relaxed">{{ $log->kegiatan }}</p>
                                    </div>
                                    <div class="bg-gray-50/80 p-3.5 rounded-xl border border-gray-100">
                                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Hasil / Luaran:</h4>
                                        <p class="text-xs text-gray-700 leading-relaxed">{{ $log->output }}</p>
                                    </div>
                                </div>

                                @if($log->feedback_dpl)
                                    <div class="bg-uin-light/40 border-t border-uin/20 p-4 flex items-start space-x-3">
                                        <div class="p-1.5 bg-uin text-white rounded-lg flex-shrink-0 mt-0.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-[10px] font-bold text-uin uppercase tracking-wider">Ulasan & Feedback DPL:</h4>
                                            <p class="text-xs text-gray-800 italic mt-0.5 leading-relaxed">"{{ $log->feedback_dpl }}"</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400 flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <p class="text-xs font-semibold text-gray-500">Belum ada jurnal logbook yang ditulis.</p>
                            <p class="text-[11px] text-gray-400 mt-1">Gunakan formulir di atas untuk mencatat kegiatan harian magang Anda.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>