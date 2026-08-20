<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                Penetapan Dosen Pembimbing (DPL)
            </h2>
            <p class="text-sm text-gray-500 mt-1">Alokasikan dosen pembimbing untuk mahasiswa yang pengajuannya telah disetujui.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
            <div class="mb-6 px-4 py-3 text-sm text-uin bg-uin-light border border-uin/20 rounded-lg shadow-sm font-medium flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Mahasiswa</th>
                                <th class="px-6 py-4 font-semibold">Instansi Magang</th>
                                <th class="px-6 py-4 font-semibold">Dosen Pembimbing</th>
                                <th class="px-6 py-4 text-center font-semibold w-1/3">Aksi / Surat Tugas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($pengajuan as $item)
                            <tr class="bg-white hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-5 align-middle">
                                    <div class="font-bold text-gray-900">{{ $item->user->name }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">NIM: {{ $item->user->nim }}</div>
                                </td>

                                <td class="px-6 py-5 align-middle font-medium text-gray-800">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-md bg-gray-100 flex items-center justify-center mr-3 text-gray-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        {{ $item->instansi }}
                                    </div>

                                    @php
                                    $suratBalasan = $item->documents->where('type', 'SURAT_PENERIMAAN')->first();
                                    @endphp

                                    <div class="mt-3 ml-11">
                                        @if($suratBalasan)
                                        <a href="{{ asset('storage/' . $suratBalasan->file_path) }}" target="_blank" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 px-2 py-1 rounded border border-blue-200 transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            Lihat Surat Balasan
                                        </a>
                                        @else
                                        <span class="inline-flex items-center text-xs font-semibold text-yellow-600 bg-yellow-50 px-2 py-1 rounded border border-yellow-200">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Surat Balasan Belum Diunggah
                                        </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-5 align-middle">
                                    @if($item->dplAssignment)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-uin-light text-uin font-semibold text-xs border border-uin/20">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $item->dplAssignment->dpl?->name ?? 'DPL Tidak Ditemukan' }}
                                    </span>
                                    @else
                                    <span class="inline-flex items-center text-red-500 font-medium text-xs bg-red-50 px-2.5 py-1 rounded-full border border-red-100">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Belum Ditetapkan
                                    </span>
                                    @endif
                                </td>

                                <td class="px-6 py-5 align-middle bg-gray-50/50">
                                    @if(!$item->dplAssignment)
                                    <form action="{{ route('admin.penetapan_dpl.store') }}" method="POST" class="flex items-center space-x-2 w-full">
                                        @csrf
                                        <input type="hidden" name="application_id" value="{{ $item->id }}">
                                        <div class="flex-1">
                                            <select name="dpl_user_id" class="text-sm rounded-md border-gray-300 focus:ring-uin focus:border-uin block w-full py-2 shadow-sm" required>
                                                <option value="" disabled selected>Pilih Dosen...</option>
                                                @foreach($dosen as $dsn)
                                                <option value="{{ $dsn->id }}">{{ $dsn->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="bg-uin text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-uin-hover focus:ring-2 focus:ring-offset-2 focus:ring-uin transition shadow-sm whitespace-nowrap">
                                            Tetapkan DPL
                                        </button>
                                    </form>
                                    @else
                                    <div class="flex justify-center">
                                        <a href="{{ route('admin.penetapan_dpl.surat', $item->dplAssignment->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-uin text-uin rounded-md text-sm font-semibold hover:bg-uin-light focus:ring-2 focus:ring-offset-2 focus:ring-uin transition shadow-sm w-full justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Cetak Surat Tugas
                                        </a>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-500">Tidak ada pengajuan yang siap untuk diberikan Dosen Pembimbing.</span>
                                    </div>
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