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

            <!-- DUA KOLOM: PRESENSI GPS & JURNAL LOGBOOK -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- KOLOM 1: PRESENSI & GPS -->
                <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 flex flex-col h-[650px]">
                    <div class="p-4 border-b border-gray-100 bg-gray-50/80 rounded-t-xl">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            Monitoring Presensi & GPS Harian
                        </h3>
                    </div>
                    <div class="overflow-y-auto flex-1 p-0 custom-scrollbar">
                        <table class="w-full text-xs text-left text-gray-600">
                            <tbody class="divide-y divide-gray-100">
                                @forelse($presensi as $abs)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3">
                                            <span class="font-bold text-gray-900 block">{{ $abs->user->name ?? '-' }}</span>
                                            <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($abs->date)->translatedFormat('d M Y') }} ({{ $abs->created_at->format('H:i') }})</span>
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase {{ $abs->type == 'masuk' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                                {{ $abs->type ?? 'Masuk' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            @if($abs->latitude && $abs->longitude)
                                                <a href="https://maps.google.com/?q={{ $abs->latitude }},{{ $abs->longitude }}" target="_blank" class="text-[11px] text-uin hover:underline block font-mono font-semibold">
                                                    📍 {{ number_format((float)$abs->latitude, 3) }}, {{ number_format((float)$abs->longitude, 3) }}
                                                </a>
                                            @else
                                                <span class="text-[11px] text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-12 text-gray-400 text-xs">Belum ada data presensi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- KOLOM 2: JURNAL LOGBOOK -->
                <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 flex flex-col h-[650px]">
                    <div class="p-4 border-b border-gray-100 bg-gray-50/80 rounded-t-xl flex justify-between items-center">
                        <h3 class="text-sm font-bold text-gray-900 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Logbook Kegiatan & Feedback DPL
                        </h3>
                    </div>
                    
                    <div class="overflow-y-auto flex-1 p-4 space-y-4 custom-scrollbar">
                        @forelse($logbooks as $log)
                            <div class="border border-gray-200 rounded-xl bg-white shadow-sm overflow-hidden">
                                <div class="bg-gray-50/80 px-4 py-2.5 border-b border-gray-100 text-xs font-bold text-gray-900 flex justify-between items-center">
                                    <span>{{ $log->user->name ?? '-' }}</span>
                                    <span class="text-gray-400 font-normal">{{ \Carbon\Carbon::parse($log->date)->translatedFormat('d M Y') }}</span>
                                </div>
                                <div class="p-4">
                                    <p class="text-xs text-gray-800 leading-relaxed mb-3">{{ $log->kegiatan }}</p>
                                    <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-100 mb-3">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Output / Hasil:</p>
                                        <p class="text-xs text-gray-700 mt-0.5">{{ $log->output }}</p>
                                    </div>
                                    <form action="{{ route('dpl.pemantauan.feedback', $log->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <div class="flex space-x-2">
                                            <input type="text" name="feedback_dpl" value="{{ $log->feedback_dpl }}" class="flex-1 text-xs border-gray-300 focus:ring-uin focus:border-uin rounded-lg" placeholder="Tuliskan catatan / review feedback...">
                                            <button type="submit" class="bg-uin text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-uin-hover transition shadow-xs">Simpan Review</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col justify-center items-center h-full text-gray-400 py-12">
                                <p class="text-xs font-medium">Belum ada logbook yang diisi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>