<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                Daftar Mahasiswa Bimbingan
            </h2>
            <p class="text-sm text-gray-500 mt-1">Pantau aktivitas, kehadiran, dan perkembangan mahasiswa magang yang Anda bimbing.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Mahasiswa Bimbingan Aktif</h3>
                        <p class="text-sm text-gray-500">Pilih mahasiswa untuk melihat detail kegiatan harian mereka.</p>
                    </div>
                    <div class="bg-uin-light text-uin px-3 py-1 rounded-full text-sm font-bold border border-uin/20">
                        Total: {{ $mahasiswa->count() }} Mahasiswa
                    </div>
                </div>
                
                <div class="p-6 bg-gray-50/30">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($mahasiswa as $item)
                            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border-b-4 border-b-transparent hover:border-b-uin flex flex-col h-full">
                                <div class="flex items-center space-x-4 mb-5">
                                    <div class="bg-uin-light p-3 rounded-full text-uin border border-uin/10">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 leading-tight">{{ $item->user->name }}</h4>
                                        <p class="text-xs text-gray-500 mt-0.5">NIM: {{ $item->user->nim }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex-grow text-sm text-gray-600 space-y-3 mb-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Instansi Tujuan:</span>
                                        <span class="font-semibold text-gray-800">{{ $item->instansi }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status:</span>
                                        <span class="px-2.5 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-[10px] font-bold uppercase tracking-wider flex items-center">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span> Aktif
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mt-auto">
                                    <a href="{{ route('dpl.pemantauan.show', $item->id) }}" class="flex items-center justify-center w-full bg-white border-2 border-uin text-uin text-sm font-bold py-2.5 rounded-lg hover:bg-uin hover:text-white transition-colors shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Pantau Kegiatan
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full flex flex-col items-center justify-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <p class="text-gray-500 font-medium text-lg">Belum ada mahasiswa yang ditetapkan.</p>
                                <p class="text-sm text-gray-400 mt-1">Daftar bimbingan Anda akan muncul di sini setelah Admin memproses penetapan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>