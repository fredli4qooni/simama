<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Rekapitulasi Nilai Akhir
                </h2>
                <p class="text-sm text-gray-500 mt-1">Daftar nilai akhir mahasiswa magang yang telah dievaluasi oleh DPL.</p>
            </div>
            <div>
                <a href="{{ route('admin.rekap.cetak') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-uin text-uin rounded-md text-sm font-semibold hover:bg-uin-light focus:ring-2 focus:ring-offset-2 focus:ring-uin transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak PDF Laporan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('error'))
                <div class="mb-6 px-4 py-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg shadow-sm font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200 tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Mahasiswa & Instansi</th>
                                <th class="px-6 py-4 font-semibold">Dosen Pembimbing</th>
                                <th class="px-3 py-4 text-center font-semibold">Disiplin</th>
                                <th class="px-3 py-4 text-center font-semibold">Teknis</th>
                                <th class="px-3 py-4 text-center font-semibold">Komunikasi</th>
                                <th class="px-3 py-4 text-center font-semibold">Inisiatif</th>
                                <th class="px-6 py-4 text-center font-bold bg-uin-light text-uin">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pengajuan as $item)
                                <tr class="bg-white hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 align-middle">
                                        <div class="font-bold text-gray-900">{{ $item->user->name }}</div>
                                        <div class="text-xs text-gray-500 mb-1.5">NIM: {{ $item->user->nim }}</div>
                                        <div class="text-[11px] font-medium bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md inline-flex items-center border border-gray-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            {{ $item->instansi }}
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4 align-middle font-medium text-gray-800">
                                        {{ $item->dplAssignment->dpl->name ?? '-' }}
                                    </td>
                                    
                                    @if($item->evaluation)
                                        <td class="px-3 py-4 align-middle text-center font-medium">{{ $item->evaluation->kedisiplinan }}</td>
                                        <td class="px-3 py-4 align-middle text-center font-medium">{{ $item->evaluation->teknis }}</td>
                                        <td class="px-3 py-4 align-middle text-center font-medium">{{ $item->evaluation->komunikasi }}</td>
                                        <td class="px-3 py-4 align-middle text-center font-medium">{{ $item->evaluation->inisiatif }}</td>
                                        <td class="px-6 py-4 align-middle text-center bg-gray-50/50">
                                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-full {{ $item->evaluation->nilai_akhir >= 80 ? 'bg-green-100 text-green-700' : ($item->evaluation->nilai_akhir >= 60 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }} font-bold text-lg border border-white shadow-sm">
                                                {{ number_format($item->evaluation->nilai_akhir, 0) }}
                                            </span>
                                        </td>
                                    @else
                                        <td colspan="5" class="px-6 py-4 text-center align-middle">
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-600 bg-red-50 border border-red-100 rounded-full">
                                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Belum Dievaluasi
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span class="text-sm font-medium">Belum ada data nilai magang yang masuk.</span>
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