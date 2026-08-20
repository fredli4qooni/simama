<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Pemantauan Magang & Konsultasi
                </h2>
                <p class="text-sm text-gray-500 mt-1">Pemantauan kelompok/mahasiswa: <span class="font-semibold text-uin">{{ $pengajuan->user->name }}</span> ({{ $pengajuan->instansi }}).</p>
            </div>
            <div>
                <a href="{{ route('dpl.pemantauan.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-50 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-[95rem] mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="px-4 py-3 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg shadow-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- BANNER INFORMASI MAGANG & ANGGOTA -->
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 p-6 border-l-4 border-l-uin">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-xl font-bold text-gray-900">{{ $pengajuan->instansi }}</h3>
                            <span class="px-2.5 py-0.5 text-xs font-bold rounded {{ $pengajuan->is_group ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700' }}">
                                {{ $pengajuan->is_group ? 'Kelompok Magang' : 'Individu' }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Masa Magang: <strong>{{ \Carbon\Carbon::parse($pengajuan->start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($pengajuan->end_date)->format('d M Y') }}</strong>
                        </p>
                    </div>

                    <!-- Anggota Kelompok -->
                    <div class="flex flex-wrap gap-2">
                        @if($pengajuan->members && $pengajuan->members->count() > 0)
                            @foreach($pengajuan->members as $m)
                                <div class="px-3 py-1 bg-gray-50 border border-gray-200 rounded-lg text-xs">
                                    <span class="font-bold text-gray-800">{{ $m->user->name }}</span>
                                    <span class="text-gray-400">({{ $m->user->nim }})</span>
                                    <span class="ml-1 text-[10px] font-bold text-uin">[{{ $m->is_leader ? 'Ketua' : 'Anggota' }}]</span>
                                </div>
                            @endforeach
                        @else
                            <div class="px-3 py-1 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-800">
                                {{ $pengajuan->user->name }} ({{ $pengajuan->user->nim }})
                            </div>
                        @endif
                    </div>
                </div>

                @php
                    $laporanAkhir = $pengajuan->documents->where('type', 'LAPORAN_AKHIR')->first();
                @endphp
                @if($laporanAkhir)
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs font-semibold text-purple-900">📄 Mahasiswa telah mengunggah Laporan Akhir Magang.</span>
                        <a href="{{ asset('storage/' . $laporanAkhir->file_path) }}" target="_blank" class="px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded text-xs font-bold shadow-sm transition">
                            Unduh Laporan Akhir (PDF)
                        </a>
                    </div>
                @endif
            </div>

            <!-- TIGA KOLOM: PRESENSI GPS, JURNAL LOGBOOK, KONSULTASI -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- KOLOM 1: PRESENSI & GPS -->
                <div class="lg:col-span-4 bg-white shadow-sm sm:rounded-xl border border-gray-100 flex flex-col h-[600px]">
                    <div class="p-4 border-b border-gray-100 bg-gray-50/80 rounded-t-xl">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            Monitoring Presensi & GPS
                        </h3>
                    </div>
                    <div class="overflow-y-auto flex-1 p-0 custom-scrollbar">
                        <table class="w-full text-xs text-left text-gray-600">
                            <tbody class="divide-y divide-gray-100">
                                @forelse($presensi as $abs)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-3 py-2.5">
                                            <span class="font-bold text-gray-900 block">{{ $abs->user->name ?? '-' }}</span>
                                            <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($abs->date)->translatedFormat('d M') }} ({{ $abs->created_at->format('H:i') }})</span>
                                        </td>
                                        <td class="px-2 py-2.5 text-center">
                                            <span class="px-1.5 py-0.5 text-[9px] font-bold rounded uppercase {{ $abs->type == 'masuk' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                                {{ $abs->type ?? 'Masuk' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2.5 text-right">
                                            @if($abs->latitude && $abs->longitude)
                                                <a href="https://maps.google.com/?q={{ $abs->latitude }},{{ $abs->longitude }}" target="_blank" class="text-[10px] text-uin hover:underline block font-mono">
                                                    📍 {{ number_format((float)$abs->latitude, 3) }}, {{ number_format((float)$abs->longitude, 3) }}
                                                </a>
                                            @else
                                                <span class="text-[10px] text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-8 text-gray-400 text-xs">Belum ada data presensi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- KOLOM 2: JURNAL LOGBOOK -->
                <div class="lg:col-span-4 bg-white shadow-sm sm:rounded-xl border border-gray-100 flex flex-col h-[600px]">
                    <div class="p-4 border-b border-gray-100 bg-gray-50/80 rounded-t-xl flex justify-between items-center">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Logbook Kegiatan
                        </h3>
                    </div>
                    
                    <div class="overflow-y-auto flex-1 p-4 space-y-4 custom-scrollbar">
                        @forelse($logbooks as $log)
                            <div class="border border-gray-200 rounded-xl bg-white shadow-sm overflow-hidden">
                                <div class="bg-gray-50/80 px-3 py-2 border-b border-gray-100 text-xs font-bold text-gray-900 flex justify-between">
                                    <span>{{ $log->user->name ?? '-' }}</span>
                                    <span class="text-gray-400 font-normal">{{ \Carbon\Carbon::parse($log->date)->translatedFormat('d M Y') }}</span>
                                </div>
                                <div class="p-3">
                                    <p class="text-xs text-gray-800 leading-relaxed mb-2">{{ $log->kegiatan }}</p>
                                    <div class="bg-gray-50 p-2 rounded border border-gray-100 mb-2.5">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase">Output:</p>
                                        <p class="text-xs text-gray-700">{{ $log->output }}</p>
                                    </div>
                                    <form action="{{ route('dpl.pemantauan.feedback', $log->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <div class="flex space-x-2">
                                            <input type="text" name="feedback_dpl" value="{{ $log->feedback_dpl }}" class="flex-1 text-xs border-gray-300 focus:ring-uin focus:border-uin rounded-md" placeholder="Beri feedback...">
                                            <button type="submit" class="bg-uin text-white px-2.5 py-1 rounded text-xs font-bold hover:bg-uin-hover">Kirim</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col justify-center items-center h-full text-gray-400">
                                <p class="text-xs font-medium">Belum ada logbook yang diisi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- KOLOM 3: KONSULTASI / CHAT -->
                <div class="lg:col-span-4 bg-white shadow-sm sm:rounded-xl border border-gray-100 flex flex-col h-[600px] relative">
                    <div class="p-4 border-b border-uin bg-uin text-white rounded-t-xl flex justify-between items-center shadow-sm z-10">
                        <div class="flex items-center">
                            <div class="w-7 h-7 bg-white/20 rounded-full flex items-center justify-center mr-2.5">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold">Ruang Konsultasi</h3>
                                <p class="text-[10px] text-green-100 opacity-90">Bimbingan Magang</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-1 p-4 overflow-y-auto bg-slate-50 space-y-3 custom-scrollbar flex flex-col-reverse">
                        <div class="flex flex-col space-y-3">
                            @forelse($pengajuan->consultations as $chat)
                                @if($chat->sender_id == auth()->id())
                                    <div class="flex justify-end">
                                        <div class="bg-uin text-white rounded-l-xl rounded-tr-xl p-2.5 max-w-[85%] shadow-sm">
                                            <p class="text-xs leading-relaxed">{{ $chat->message }}</p>
                                            <span class="text-[8px] text-uin-light block mt-1 text-right">{{ $chat->created_at->format('H:i') }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex justify-start">
                                        <div class="bg-white border border-gray-200 text-gray-800 rounded-r-xl rounded-tl-xl p-2.5 max-w-[85%] shadow-sm">
                                            <p class="text-[9px] font-bold text-gray-400 mb-0.5">{{ $chat->sender->name }}</p>
                                            <p class="text-xs leading-relaxed">{{ $chat->message }}</p>
                                            <span class="text-[8px] text-gray-400 block mt-1">{{ $chat->created_at->format('H:i') }}</span>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div class="text-center text-gray-400 opacity-70 mt-10">
                                    <p class="text-xs">Mulai percakapan dengan mahasiswa.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="p-3 border-t border-gray-200 bg-white rounded-b-xl z-10">
                        <form action="{{ route('konsultasi.store', $pengajuan->id) }}" method="POST" class="flex items-center space-x-2">
                            @csrf
                            <input type="text" name="message" class="flex-1 bg-gray-100 border-transparent focus:bg-white focus:border-uin focus:ring-uin rounded-full text-xs px-3.5 py-2" placeholder="Ketik pesan bimbingan..." required autocomplete="off">
                            <button type="submit" class="bg-uin text-white rounded-full p-2 hover:bg-uin-hover transition shadow-sm">
                                <svg class="w-4 h-4 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>