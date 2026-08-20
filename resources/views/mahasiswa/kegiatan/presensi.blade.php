<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                Presensi Harian & GPS Geolocation
            </h2>
            <p class="text-sm text-gray-500 mt-1">Presensi dilakukan 2 kali sehari (Masuk & Pulang) dengan pendeteksian titik lokasi GPS instansi.</p>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        type: 'masuk',
        status: 'hadir',
        latitude: '',
        longitude: '',
        gpsStatus: 'Mendeteksi lokasi GPS...',
        gpsReady: false,
        initGps() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.latitude = position.coords.latitude;
                        this.longitude = position.coords.longitude;
                        this.gpsStatus = 'Lokasi GPS Terdeteksi: ' + position.coords.latitude.toFixed(5) + ', ' + position.coords.longitude.toFixed(5);
                        this.gpsReady = true;
                    },
                    (error) => {
                        this.gpsStatus = 'Gagal mengakses GPS: ' + error.message + '. Silakan izinkan akses lokasi di browser Anda.';
                        this.gpsReady = false;
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            } else {
                this.gpsStatus = 'Browser Anda tidak mendukung fitur Geolocation GPS.';
                this.gpsReady = false;
            }
        }
    }" x-init="initGps()">
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

            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-uin text-white flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-2 rounded-lg mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Presensi Hari Ini</h3>
                            <p class="text-xs text-white/80">Instansi: {{ $pengajuan->instansi }}</p>
                        </div>
                    </div>
                    <div class="text-sm font-medium bg-white/10 px-3 py-1 rounded-full">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Status Ringkasan Hari Ini -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="p-4 rounded-lg border {{ $hariIniMasuk ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">1. Presensi Masuk (Check-In)</span>
                                @if($hariIniMasuk)
                                    <span class="px-2 py-0.5 bg-green-600 text-white text-[11px] font-bold rounded">TERCATAT</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-200 text-gray-700 text-[11px] font-bold rounded">BELUM</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-4 rounded-lg border {{ $hariIniPulang ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">2. Presensi Pulang (Check-Out)</span>
                                @if($hariIniPulang)
                                    <span class="px-2 py-0.5 bg-green-600 text-white text-[11px] font-bold rounded">TERCATAT</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-200 text-gray-700 text-[11px] font-bold rounded">BELUM</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($hariIniMasuk && $hariIniPulang)
                        <div class="p-6 bg-green-50 border border-green-200 rounded-xl flex flex-col items-center justify-center text-center">
                            <div class="w-14 h-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h4 class="text-base font-bold text-gray-900 mb-1">Presensi Hari Ini Lengkap!</h4>
                            <p class="text-xs text-gray-600">Anda sudah menyelesaikan presensi Masuk dan Pulang untuk hari ini.</p>
                        </div>
                    @else
                        <form action="{{ route('mahasiswa.presensi.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="latitude" :value="latitude">
                            <input type="hidden" name="longitude" :value="longitude">

                            <!-- Status Lokasi GPS -->
                            <div class="p-3.5 rounded-lg border flex items-center justify-between" :class="gpsReady ? 'bg-green-50 border-green-200 text-green-800' : 'bg-amber-50 border-amber-200 text-amber-800'">
                                <div class="flex items-center text-xs">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" :class="gpsReady ? 'text-green-600' : 'text-amber-600 animate-spin'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    <span x-text="gpsStatus" class="font-medium"></span>
                                </div>
                                <button type="button" @click="initGps()" class="text-xs font-bold underline ml-3 flex-shrink-0">Refresh GPS</button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Tipe Presensi *</label>
                                    <select name="type" x-model="type" class="block w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-uin focus:ring-uin" required>
                                        <option value="masuk" {{ $hariIniMasuk ? 'disabled' : '' }}>Masuk (Pagi / Datang)</option>
                                        <option value="pulang" {{ $hariIniPulang ? 'disabled' : '' }}>Pulang (Sore / Selesai)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Status Kehadiran *</label>
                                    <select name="status" x-model="status" class="block w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-uin focus:ring-uin" required>
                                        <option value="hadir">Hadir di Lokasi Instansi</option>
                                        <option value="izin">Izin</option>
                                        <option value="sakit">Sakit</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan (Opsional)</label>
                                    <input type="text" name="keterangan" placeholder="Contoh: Bekerja di Divisi IT" class="block w-full rounded-md border-gray-300 text-xs shadow-sm focus:border-uin focus:ring-uin">
                                </div>
                            </div>

                            <div class="flex justify-end pt-3 border-t border-gray-100">
                                <button type="submit" :disabled="!gpsReady" class="inline-flex items-center px-5 py-2 border border-transparent text-xs font-bold rounded-md text-white bg-uin hover:bg-uin-hover disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-md">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Kirim Presensi Sekarang
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Riwayat Presensi -->
            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-bold text-gray-900">Riwayat Presensi & Koordinat GPS Anda</h3>
                    <p class="text-xs text-gray-500">Daftar kehadiran Masuk & Pulang Anda selama masa magang.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left text-gray-600">
                        <thead class="text-[11px] text-gray-700 uppercase bg-gray-50 border-b border-gray-200 tracking-wider">
                            <tr>
                                <th class="px-5 py-3 font-semibold">Tanggal & Waktu</th>
                                <th class="px-5 py-3 font-semibold text-center">Tipe</th>
                                <th class="px-5 py-3 font-semibold text-center">Status</th>
                                <th class="px-5 py-3 font-semibold">Titik GPS</th>
                                <th class="px-5 py-3 font-semibold">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($presensi as $item)
                                <tr class="bg-white hover:bg-gray-50">
                                    <td class="px-5 py-3 font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}
                                        <span class="text-gray-400 block text-[10px]">{{ $item->created_at->format('H:i:s') }} WIB</span>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="px-2 py-0.5 rounded font-bold uppercase text-[10px] {{ $item->type == 'masuk' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ $item->type ?? 'Masuk' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        @if($item->status == 'hadir')
                                            <span class="px-2 py-0.5 text-[10px] font-bold text-green-700 bg-green-50 border border-green-200 rounded-full">HADIR</span>
                                        @elseif($item->status == 'izin')
                                            <span class="px-2 py-0.5 text-[10px] font-bold text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-full">IZIN</span>
                                        @else
                                            <span class="px-2 py-0.5 text-[10px] font-bold text-red-700 bg-red-50 border border-red-200 rounded-full">SAKIT</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-gray-600 font-mono">
                                        @if($item->latitude && $item->longitude)
                                            <a href="https://maps.google.com/?q={{ $item->latitude }},{{ $item->longitude }}" target="_blank" class="text-uin hover:underline flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                                {{ number_format((float)$item->latitude, 4) }}, {{ number_format((float)$item->longitude, 4) }}
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-gray-600">
                                        {{ $item->keterangan ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                        Belum ada riwayat presensi yang dicatat.
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