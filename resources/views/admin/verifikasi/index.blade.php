<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                Verifikasi Pengajuan Magang Kelompok & Individu
            </h2>
            <p class="text-sm text-gray-500 mt-1">Tinjau berkas persyaratan (KTM, Transkrip & Slip SPP) setiap anggota kelompok dan berikan persetujuan.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 px-4 py-3 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg shadow-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left text-gray-600">
                        <thead class="text-[11px] text-gray-700 uppercase bg-gray-50 border-b border-gray-200 tracking-wider">
                            <tr>
                                <th scope="col" class="px-5 py-4 font-semibold w-1/4">Pemohon & Anggota</th>
                                <th scope="col" class="px-5 py-4 font-semibold w-1/4">Instansi & Waktu</th>
                                <th scope="col" class="px-5 py-4 font-semibold w-1/4">Berkas Terunggah</th>
                                <th scope="col" class="px-4 py-4 text-center font-semibold">Status</th>
                                <th scope="col" class="px-5 py-4 text-center font-semibold">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($pengajuan as $item)
                                <tr class="bg-white hover:bg-gray-50 transition-colors">
                                    
                                    <!-- MAHASISWA / KELOMPOK -->
                                    <td class="px-5 py-4 align-top">
                                        <div class="flex items-center gap-1.5 mb-1.5">
                                            <span class="px-1.5 py-0.5 text-[9px] font-bold rounded uppercase {{ $item->is_group ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-700' }}">
                                                {{ $item->is_group ? 'Kelompok' : 'Individu' }}
                                            </span>
                                            <span class="text-[11px] font-bold text-gray-900">{{ $item->user->name }} (Ketua)</span>
                                        </div>
                                        <div class="text-[11px] text-gray-500">NIM: {{ $item->user->nim ?? '-' }}</div>

                                        @if($item->members && $item->members->count() > 1)
                                            <div class="mt-2 pt-2 border-t border-gray-100 space-y-1">
                                                <p class="text-[10px] font-bold text-gray-400 uppercase">Anggota Lainnya:</p>
                                                @foreach($item->members as $m)
                                                    @if($m->user_id !== $item->user_id)
                                                        <div class="text-[11px] text-gray-700">
                                                            • {{ $m->user->name ?? '-' }} <span class="text-gray-400">({{ $m->user->nim ?? '-' }})</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <!-- INSTANSI & WAKTU -->
                                    <td class="px-5 py-4 align-top">
                                        <div class="font-bold text-gray-800">{{ $item->instansi }}</div>
                                        <div class="text-[11px] text-gray-500 mt-1 flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-[10px] text-gray-400 mt-1">{{ $item->alamat ?? $item->alamat_instansi }}</div>
                                    </td>
                                    
                                    <!-- DOKUMEN -->
                                    <td class="px-5 py-4 align-top">
                                        <div class="flex flex-col space-y-1.5">
                                            @forelse($item->documents as $doc)
                                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-uin hover:underline font-medium flex items-center">
                                                    <span class="px-1.5 py-0.5 bg-uin-light text-uin text-[10px] font-bold rounded mr-1.5">{{ $doc->type }}</span>
                                                    <span class="text-[11px] text-gray-700 truncate max-w-[150px]">{{ $doc->user->name ?? 'Pemohon' }}</span>
                                                </a>
                                            @empty
                                                <span class="text-gray-400 text-[11px]">Belum ada berkas terunggah.</span>
                                            @endforelse
                                        </div>
                                    </td>

                                    <!-- STATUS -->
                                    <td class="px-4 py-4 text-center align-top">
                                        @if($item->status == 'pending')
                                            <span class="inline-flex items-center bg-yellow-50 text-yellow-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-yellow-200">Pending</span>
                                        @elseif($item->status == 'approved')
                                            <span class="inline-flex items-center bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-blue-200">Disetujui</span>
                                        @elseif($item->status == 'rejected')
                                            <span class="inline-flex items-center bg-red-50 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-red-200">Ditolak</span>
                                        @elseif($item->status == 'active')
                                            <span class="inline-flex items-center bg-green-50 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-green-200">Aktif</span>
                                        @elseif($item->status == 'selesai')
                                            <span class="inline-flex items-center bg-purple-50 text-purple-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-purple-200">Selesai</span>
                                        @endif
                                    </td>

                                    <!-- FORM AKSI -->
                                    <td class="px-5 py-4 min-w-[240px] align-top bg-gray-50/50">
                                        @if($item->status == 'pending')
                                            <form action="{{ route('admin.verifikasi.update', $item->id) }}" method="POST" class="space-y-2">
                                                @csrf
                                                @method('PATCH')
                                                
                                                <select name="status" class="bg-white border border-gray-300 text-gray-900 text-xs rounded-md focus:ring-uin focus:border-uin block w-full p-1.5" required>
                                                    <option value="" disabled selected>Pilih Keputusan...</option>
                                                    <option value="approved">Setujui (Approve)</option>
                                                    <option value="rejected">Tolak (Reject)</option>
                                                </select>
                                                
                                                <textarea name="catatan_admin" rows="2" class="block p-1.5 w-full text-xs text-gray-900 bg-white rounded-md border border-gray-300 focus:ring-uin focus:border-uin" placeholder="Catatan verifikasi..."></textarea>
                                                
                                                <button type="submit" class="w-full text-white bg-uin hover:bg-uin-hover font-bold rounded-md text-xs px-3 py-1.5 transition-colors shadow-sm">
                                                    Simpan Keputusan
                                                </button>
                                            </form>
                                        @else
                                            <div class="text-xs text-gray-600 bg-white p-2.5 rounded-md border border-gray-200">
                                                <span class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Catatan:</span>
                                                <p class="italic">{{ $item->catatan_admin ?? 'Tidak ada catatan.' }}</p>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        Belum ada data pengajuan magang yang masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>