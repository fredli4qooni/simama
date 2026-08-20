<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                Jurnal Logbook & Konsultasi
            </h2>
            <p class="text-sm text-gray-500 mt-1">Catat aktivitas harian dan diskusikan perkembangan magang bersama DPL Anda.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-[95rem] mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 px-4 py-3 text-sm text-uin bg-uin-light border border-uin/20 rounded-lg shadow-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 bg-uin text-white flex items-center">
                            <h3 class="text-sm font-bold flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Tulis Jurnal Kegiatan Baru
                            </h3>
                        </div>
                        <div class="p-5">
                            <form action="{{ route('mahasiswa.logbook.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700">Tanggal Kegiatan</label>
                                    <input class="mt-1 block w-full md:w-1/3 border-gray-300 focus:border-uin focus:ring-uin rounded-md shadow-sm text-sm" type="date" name="date" value="{{ old('date', \Carbon\Carbon::today()->format('Y-m-d')) }}" required max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700">Uraian Kegiatan</label>
                                        <textarea name="kegiatan" rows="2" class="mt-1 block w-full border-gray-300 focus:border-uin focus:ring-uin rounded-md shadow-sm text-sm" required placeholder="Ceritakan detail tugas..."></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700">Hasil / Output</label>
                                        <textarea name="output" rows="2" class="mt-1 block w-full border-gray-300 focus:border-uin focus:ring-uin rounded-md shadow-sm text-sm" required placeholder="Misal: Laporan selesai..."></textarea>
                                    </div>
                                </div>
                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="bg-uin text-white px-5 py-2 rounded-md text-xs font-bold hover:bg-uin-hover shadow-sm">Simpan Logbook</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 bg-gray-50/80">
                            <h3 class="text-sm font-bold text-gray-900">Riwayat Jurnal & Catatan DPL</h3>
                        </div>
                        <div class="p-5 space-y-4 h-[400px] overflow-y-auto custom-scrollbar">
                            @forelse($logbooks as $log)
                                <div class="border border-gray-200 rounded-lg shadow-sm">
                                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-100 text-xs font-bold text-gray-700 flex justify-between">
                                        <span>{{ \Carbon\Carbon::parse($log->date)->translatedFormat('l, d F Y') }}</span>
                                    </div>
                                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <h4 class="text-[10px] font-bold text-gray-400 uppercase">Kegiatan:</h4>
                                            <p class="text-sm text-gray-800">{{ $log->kegiatan }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-[10px] font-bold text-gray-400 uppercase">Output:</h4>
                                            <p class="text-sm text-gray-800">{{ $log->output }}</p>
                                        </div>
                                    </div>
                                    @if($log->feedback_dpl)
                                        <div class="bg-blue-50/50 border-t border-gray-100 p-3 flex items-start">
                                            <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <div>
                                                <h4 class="text-[10px] font-bold text-blue-800 uppercase">Komentar DPL:</h4>
                                                <p class="text-sm text-blue-900 italic">"{{ $log->feedback_dpl }}"</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-10 text-gray-400 text-sm">Belum ada jurnal yang ditulis.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                @php 
                    // Mengambil data aplikasi magang mahasiswa yang sedang aktif untuk mengaitkan chat
                    $currentPengajuan = auth()->user()->magangApplications()->whereIn('status', ['approved', 'active'])->latest()->first();
                @endphp

                <div class="lg:col-span-4 bg-white shadow-sm sm:rounded-xl border border-gray-100 flex flex-col h-[700px] relative">
                    <div class="p-4 border-b border-uin bg-uin text-white rounded-t-xl flex justify-between items-center shadow-sm z-10">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold">Konsultasi DPL</h3>
                                <p class="text-[10px] text-green-100 opacity-90">Tanya jawab terkait magang</p>
                            </div>
                        </div>
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-300 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-400"></span>
                        </span>
                    </div>
                    
                    <div class="flex-1 p-4 overflow-y-auto bg-slate-50 space-y-4 custom-scrollbar flex flex-col-reverse">
                        <div class="flex flex-col space-y-4">
                            @if($currentPengajuan)
                                @forelse($currentPengajuan->consultations as $chat)
                                    @if($chat->sender_id == auth()->id())
                                        <div class="flex justify-end">
                                            <div class="bg-uin text-white rounded-l-xl rounded-tr-xl p-3 max-w-[85%] shadow-sm">
                                                <p class="text-sm leading-relaxed">{{ $chat->message }}</p>
                                                <span class="text-[9px] text-uin-light block mt-1 text-right">{{ $chat->created_at->format('H:i') }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex justify-start">
                                            <div class="bg-white border border-gray-200 text-gray-800 rounded-r-xl rounded-tl-xl p-3 max-w-[85%] shadow-sm">
                                                <p class="text-[10px] font-bold text-gray-400 mb-0.5">{{ $chat->sender->name }} (DPL)</p>
                                                <p class="text-sm leading-relaxed">{{ $chat->message }}</p>
                                                <span class="text-[9px] text-gray-400 block mt-1">{{ $chat->created_at->format('H:i') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center text-gray-400 opacity-70 mt-10">
                                        <svg class="w-10 h-10 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        <p class="text-xs">Sapa Dosen Pembimbing Anda!</p>
                                    </div>
                                @endforelse
                            @else
                                <div class="text-center text-gray-400 mt-10 text-xs">Anda harus memiliki pengajuan magang yang aktif untuk menggunakan fitur ini.</div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-3 border-t border-gray-200 bg-white rounded-b-xl z-10">
                        @if($currentPengajuan)
                        <form action="{{ route('konsultasi.store', $currentPengajuan->id) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            <input type="text" name="message" class="flex-1 bg-gray-100 border-transparent focus:bg-white focus:border-uin focus:ring-uin rounded-full text-sm px-4 py-2.5 transition-colors" placeholder="Ketik pesan konsultasi..." required autocomplete="off">
                            <button type="submit" class="bg-uin text-white rounded-full p-2.5 hover:bg-uin-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uin transition shadow-sm">
                                <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</x-app-layout>