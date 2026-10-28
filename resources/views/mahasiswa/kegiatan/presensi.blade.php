<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-4">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900 leading-tight">
                    Presensi Harian & GPS Geolocation
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Presensi dilakukan 2 kali sehari (Masuk & Pulang) dengan pendeteksian titik lokasi GPS instansi secara visual di peta.</p>
            </div>
            <a href="{{ route('mahasiswa.presensi.cetak') }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-xs hover:shadow transition whitespace-nowrap w-full sm:w-auto text-center" title="Cetak / Unduh Berkas Rekap Presensi Resmi Format PDF">
                <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Cetak Presensi Resmi (PDF)</span>
            </a>
        </div>
    </x-slot>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}" />
    <style>
        #map-presensi {
            height: 280px !important;
            min-height: 280px !important;
            width: 100% !important;
            display: block !important;
            position: relative !important;
            z-index: 10 !important;
        }
        @media (min-width: 640px) {
            #map-presensi {
                height: 350px !important;
                min-height: 350px !important;
            }
        }
        #modal-map {
            height: 260px !important;
            min-height: 260px !important;
            width: 100% !important;
            display: block !important;
            position: relative !important;
            z-index: 10 !important;
        }
        @media (min-width: 640px) {
            #modal-map {
                height: 320px !important;
                min-height: 320px !important;
            }
        }
        .custom-gps-pin {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .gps-pulse {
            position: absolute;
            width: 38px;
            height: 38px;
            background-color: rgba(37, 99, 235, 0.35);
            border-radius: 50%;
            animation: gpsPing 1.8s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        .gps-dot {
            position: relative;
            width: 18px;
            height: 18px;
            background-color: #1d4ed8;
            border: 3px solid #ffffff;
            border-radius: 50%;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4);
        }
        @keyframes gpsPing {
            75%, 100% {
                transform: scale(2.2);
                opacity: 0;
            }
        }
        .leaflet-container {
            font-family: inherit;
            background: #e2e8f0;
        }
    </style>
    @endpush

    @push('head_scripts')
    <script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
    @endpush

    <!-- Fallback synchronous load -->
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}" />
    <script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>

    <div class="py-4 sm:py-6 lg:py-8" x-data="{
        type: '{{ $hariIniMasuk && !$hariIniPulang ? 'pulang' : 'masuk' }}',
        status: 'hadir',
        latitude: '',
        longitude: '',
        accuracy: null,
        gpsStatus: 'Mendeteksi lokasi GPS...',
        gpsReady: false,
        gpsLoading: true,
        map: null,
        marker: null,
        accuracyCircle: null,

        // Modal Riwayat Peta
        modalOpen: false,
        modalTitle: '',
        modalCoords: '',
        modalMap: null,
        modalMarker: null,

        init() {
            this.$nextTick(() => {
                this.initMap();
                this.initGps();
            });
            window.addEventListener('resize', () => {
                if (this.map) this.map.invalidateSize();
            });
        },

        getPinIcon() {
            if (typeof L === 'undefined') return null;
            return L.divIcon({
                className: 'custom-gps-pin',
                html: '<div style=\'position: relative; display: flex; align-items: center; justify-content: center; width: 38px; height: 38px;\'><div class=\'gps-pulse\'></div><div class=\'gps-dot\'></div></div>',
                iconSize: [38, 38],
                iconAnchor: [19, 19],
                popupAnchor: [0, -19]
            });
        },

        initMap() {
            const startInit = () => {
                if (typeof L === 'undefined') {
                    setTimeout(startInit, 50);
                    return;
                }

                const mapElem = document.getElementById('map-presensi');
                if (!mapElem) {
                    setTimeout(startInit, 50);
                    return;
                }

                if (this.map) {
                    this.map.invalidateSize();
                    return;
                }

                L.Icon.Default.imagePath = '{{ asset('vendor/leaflet/images') }}/';

                const initialLat = this.latitude ? parseFloat(this.latitude) : -5.38556;
                const initialLng = this.longitude ? parseFloat(this.longitude) : 105.29664;
                const initialZoom = this.latitude ? 16 : 14;

                this.map = L.map('map-presensi', {
                    zoomControl: true,
                    attributionControl: false
                }).setView([initialLat, initialLng], initialZoom);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(this.map);

                L.control.attribution({ position: 'bottomright', prefix: false }).addTo(this.map);

                // Izinkan klik pada peta untuk menyesuaikan titik
                this.map.on('click', (e) => {
                    this.latitude = e.latlng.lat;
                    this.longitude = e.latlng.lng;
                    this.gpsStatus = 'Lokasi GPS (Disesuaikan manual): ' + e.latlng.lat.toFixed(5) + ', ' + e.latlng.lng.toFixed(5);
                    this.gpsReady = true;
                    this.gpsLoading = false;
                    this.updateMarker(e.latlng.lat, e.latlng.lng, null);
                });

                if (this.latitude && this.longitude) {
                    this.updateMarker(this.latitude, this.longitude, this.accuracy);
                }

                setTimeout(() => { if (this.map) this.map.invalidateSize(); }, 150);
                setTimeout(() => { if (this.map) this.map.invalidateSize(); }, 500);
                setTimeout(() => { if (this.map) this.map.invalidateSize(); }, 1000);
            };

            startInit();
        },

        updateMarker(lat, lng, acc) {
            if (!this.map || typeof L === 'undefined') return;
            const latNum = parseFloat(lat);
            const lngNum = parseFloat(lng);

            const icon = this.getPinIcon();

            if (this.marker) {
                this.marker.setLatLng([latNum, lngNum]);
            } else {
                this.marker = L.marker([latNum, lngNum], {
                    draggable: true,
                    icon: icon
                }).addTo(this.map);

                this.marker.on('dragend', (event) => {
                    const pos = event.target.getLatLng();
                    this.latitude = pos.lat;
                    this.longitude = pos.lng;
                    this.gpsStatus = 'Lokasi GPS (Digeser manual): ' + pos.lat.toFixed(5) + ', ' + pos.lng.toFixed(5);
                    this.marker.bindPopup(`<b>Titik Presensi Anda</b><br><span style=\'font-size:11px;\'>Lat: ${pos.lat.toFixed(6)}<br>Lng: ${pos.lng.toFixed(6)}</span>`).openPopup();
                });
            }

            // Lingkaran akurasi jika ada
            if (acc) {
                if (this.accuracyCircle) {
                    this.accuracyCircle.setLatLng([latNum, lngNum]);
                    this.accuracyCircle.setRadius(acc);
                } else {
                    this.accuracyCircle = L.circle([latNum, lngNum], {
                        radius: acc,
                        color: '#2563eb',
                        fillColor: '#60a5fa',
                        fillOpacity: 0.15,
                        weight: 1
                    }).addTo(this.map);
                }
            }

            this.marker.bindPopup(`<b>Titik Presensi Anda</b><br><span style=\'font-size:11px;\'>Lat: ${latNum.toFixed(6)}<br>Lng: ${lngNum.toFixed(6)}</span>`).openPopup();
            this.map.flyTo([latNum, lngNum], 16, { duration: 1.2 });
            setTimeout(() => { if (this.map) this.map.invalidateSize(); }, 250);
        },

        initGps() {
            this.gpsLoading = true;
            this.gpsStatus = 'Mendeteksi koordinat GPS perangkat Anda...';

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.latitude = position.coords.latitude;
                        this.longitude = position.coords.longitude;
                        this.accuracy = position.coords.accuracy || null;
                        this.gpsStatus = 'Lokasi GPS Terdeteksi: ' + position.coords.latitude.toFixed(5) + ', ' + position.coords.longitude.toFixed(5);
                        this.gpsReady = true;
                        this.gpsLoading = false;

                        if (this.map) {
                            this.updateMarker(this.latitude, this.longitude, this.accuracy);
                        } else {
                            this.initMap();
                        }
                    },
                    (error) => {
                        let msg = error.message;
                        if (error.code === 1) {
                            msg = 'Izin lokasi belum diberikan. Mohon klik Izinkan (Allow) lokasi pada browser atau pilih titik manual di peta.';
                        } else if (error.code === 2) {
                            msg = 'Informasi lokasi GPS tidak tersedia. Anda dapat menentukan titik manual di peta.';
                        } else if (error.code === 3) {
                            msg = 'Waktu permintaan GPS habis. Klik tombol Refresh GPS atau tentukan titik manual di peta.';
                        }
                        this.gpsStatus = msg;
                        this.gpsLoading = false;
                        if (!this.map) {
                            this.initMap();
                        }
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            } else {
                this.gpsStatus = 'Browser tidak mendukung Geolocation GPS. Silakan klik titik langsung pada peta.';
                this.gpsLoading = false;
                if (!this.map) {
                    this.initMap();
                }
            }
        },

        openHistoryMap(lat, lng, title) {
            this.modalTitle = title;
            this.modalCoords = `Latitude: ${lat}, Longitude: ${lng}`;
            this.modalOpen = true;

            const startModalInit = () => {
                if (typeof L === 'undefined') {
                    setTimeout(startModalInit, 50);
                    return;
                }
                const modalMapElem = document.getElementById('modal-map');
                if (!modalMapElem) return;

                L.Icon.Default.imagePath = '{{ asset('vendor/leaflet/images') }}/';

                const latNum = parseFloat(lat);
                const lngNum = parseFloat(lng);

                if (!this.modalMap) {
                    this.modalMap = L.map('modal-map', {
                        zoomControl: true,
                        attributionControl: false
                    }).setView([latNum, lngNum], 16);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(this.modalMap);

                    this.modalMarker = L.marker([latNum, lngNum]).addTo(this.modalMap);
                } else {
                    this.modalMap.setView([latNum, lngNum], 16);
                    this.modalMarker.setLatLng([latNum, lngNum]);
                }

                this.modalMarker.bindPopup(`<b>${title}</b><br><span style=\'font-size:11px;\'>Lat: ${latNum.toFixed(6)}<br>Lng: ${lngNum.toFixed(6)}</span>`).openPopup();
                setTimeout(() => { if (this.modalMap) this.modalMap.invalidateSize(); }, 250);
            };

            this.$nextTick(() => { startModalInit(); });
        },

        closeHistoryMap() {
            this.modalOpen = false;
        }
    }">
        <div class="max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            
            @if (session('success'))
                <div class="p-3.5 sm:p-4 text-xs sm:text-sm text-green-800 bg-green-50 border border-green-200 rounded-xl shadow-xs font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2.5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="p-3.5 sm:p-4 text-xs sm:text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl shadow-xs font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2.5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white shadow-xs rounded-xl sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 bg-uin text-white flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-4">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-2 sm:p-2.5 rounded-xl mr-3 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base sm:text-lg font-bold leading-tight">Presensi Hari Ini</h3>
                            <p class="text-xs text-white/80 truncate">Instansi: {{ $pengajuan->instansi }}</p>
                        </div>
                    </div>
                    <div class="text-[11px] sm:text-xs font-bold bg-white/15 px-3.5 py-1.5 rounded-full flex items-center space-x-2 border border-white/20 shadow-xs self-start sm:self-auto flex-wrap">
                        <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                        <span class="text-white/40">•</span>
                        <span x-data="{ time: '' }" x-init="time = new Date().toLocaleTimeString('id-ID', { timeZone: 'Asia/Jakarta', hour12: false }); setInterval(() => time = new Date().toLocaleTimeString('id-ID', { timeZone: 'Asia/Jakarta', hour12: false }), 1000)" class="font-mono text-emerald-300 font-black" x-text="time + ' WIB'"></span>
                    </div>
                </div>
                
                <div class="p-4 sm:p-6">
                    <!-- Status Ringkasan Hari Ini -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <div class="p-3.5 sm:p-4 rounded-xl border {{ $hariIniMasuk ? 'bg-green-50/80 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 block">1. Presensi Masuk (Check-In)</span>
                                    <span class="text-xs font-semibold text-gray-800 mt-0.5 block">
                                        {{ ($hariIniMasuk && is_object($hariIniMasuk)) ? 'Tercatat ' . $hariIniMasuk->created_at->format('H:i') . ' WIB' : ($hariIniMasuk ? 'Tercatat' : 'Belum Melakukan Presensi') }}
                                    </span>
                                </div>
                                @if($hariIniMasuk)
                                    <span class="px-2.5 py-1 bg-green-600 text-white text-[10px] font-bold rounded-lg shadow-xs shrink-0">TERCATAT</span>
                                @else
                                    <span class="px-2.5 py-1 bg-gray-200 text-gray-600 text-[10px] font-bold rounded-lg shrink-0">BELUM</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-3.5 sm:p-4 rounded-xl border {{ $hariIniPulang ? 'bg-green-50/80 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 block">2. Presensi Pulang (Check-Out)</span>
                                    <span class="text-xs font-semibold text-gray-800 mt-0.5 block">
                                        {{ ($hariIniPulang && is_object($hariIniPulang)) ? 'Tercatat ' . $hariIniPulang->created_at->format('H:i') . ' WIB' : ($hariIniPulang ? 'Tercatat' : 'Belum Melakukan Presensi') }}
                                    </span>
                                </div>
                                @if($hariIniPulang)
                                    <span class="px-2.5 py-1 bg-green-600 text-white text-[10px] font-bold rounded-lg shadow-xs shrink-0">TERCATAT</span>
                                @else
                                    <span class="px-2.5 py-1 bg-gray-200 text-gray-600 text-[10px] font-bold rounded-lg shrink-0">BELUM</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($hariIniMasuk && $hariIniPulang)
                        <div class="p-6 sm:p-8 bg-green-50 border border-green-200 rounded-xl sm:rounded-2xl flex flex-col items-center justify-center text-center">
                            <div class="w-14 h-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-3 shadow-xs">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-gray-900 mb-1">Presensi Hari Ini Lengkap!</h4>
                            <p class="text-xs sm:text-sm text-gray-600 max-w-md">Anda telah menyelesaikan presensi Masuk dan Pulang hari ini dengan koordinat GPS instansi tercatat di sistem.</p>
                        </div>
                    @else
                        <form action="{{ route('mahasiswa.presensi.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="latitude" :value="latitude">
                            <input type="hidden" name="longitude" :value="longitude">

                            <!-- Status Lokasi GPS Indicator Bar -->
                            <div class="p-3 sm:p-3.5 rounded-xl border flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 transition-colors duration-200" :class="gpsReady ? 'bg-green-50 border-green-200 text-green-800' : (gpsLoading ? 'bg-blue-50 border-blue-200 text-blue-800' : 'bg-amber-50 border-amber-200 text-amber-800')">
                                <div class="flex items-center text-xs min-w-0">
                                    <svg class="w-4 h-4 mr-2 shrink-0" :class="gpsLoading ? 'text-blue-600 animate-spin' : (gpsReady ? 'text-green-600' : 'text-amber-600')" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <template x-if="gpsLoading">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </template>
                                        <template x-if="!gpsLoading && gpsReady">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </template>
                                        <template x-if="!gpsLoading && !gpsReady">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </template>
                                    </svg>
                                    <span x-text="gpsStatus" class="font-medium break-words"></span>
                                </div>
                                <button type="button" @click="initGps()" class="text-xs font-bold text-uin hover:underline shrink-0 flex items-center self-end sm:self-auto py-1 px-2 rounded-lg bg-white/60 sm:bg-transparent">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Refresh GPS
                                </button>
                            </div>

                            <!-- PETA LOKASI INTERAKTIF (LEAFLET.JS MAP) -->
                            <div class="rounded-xl border border-gray-200 overflow-hidden bg-gray-50/50 shadow-xs">
                                <div class="px-3.5 sm:px-4 py-2 sm:py-2.5 bg-gray-100/80 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1.5 sm:gap-2">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-uin shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                        <span class="text-xs font-bold text-gray-800">Peta Lokasi Presensi (Live Map)</span>
                                        <span class="text-[10px] font-semibold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full" x-show="gpsReady">Sinyal Terkunci</span>
                                    </div>
                                    <div class="text-[11px] text-gray-500 font-mono" x-show="latitude && longitude">
                                        <span x-text="'Koordinat: ' + Number(latitude).toFixed(5) + ', ' + Number(longitude).toFixed(5)"></span>
                                    </div>
                                </div>

                                <!-- Kontainer Peta -->
                                <div id="map-presensi" class="w-full bg-slate-100"></div>

                                <div class="px-3.5 sm:px-4 py-2 sm:py-2.5 bg-white border-t border-gray-100 flex flex-wrap items-center justify-between text-[11px] text-gray-500 gap-2">
                                    <div class="flex items-center">
                                        <span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-600 mr-1.5 shrink-0 animate-pulse"></span>
                                        <span>Titik pin biru menunjukkan posisi Anda. Anda dapat <b>menggeser pin</b> atau <b>mengeklik peta</b> jika posisi GPS bergeser.</span>
                                    </div>
                                    <button type="button" @click="if (latitude && longitude && map) map.flyTo([parseFloat(latitude), parseFloat(longitude)], 17)" class="text-xs font-semibold text-blue-600 hover:text-blue-800 underline active:scale-95 shrink-0">
                                        Pusatkan ke Pin
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Tipe Presensi *</label>
                                    <select name="type" x-model="type" class="block w-full rounded-xl border-gray-300 text-xs shadow-xs focus:border-uin focus:ring-uin py-2.5" required>
                                        <option value="masuk" {{ $hariIniMasuk ? 'disabled' : '' }}>Masuk (Pagi / Datang)</option>
                                        <option value="pulang" {{ $hariIniPulang ? 'disabled' : '' }}>Pulang (Sore / Selesai)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Status Kehadiran *</label>
                                    <select name="status" x-model="status" class="block w-full rounded-xl border-gray-300 text-xs shadow-xs focus:border-uin focus:ring-uin py-2.5" required>
                                        <option value="hadir">Hadir di Lokasi Instansi</option>
                                        <option value="izin">Izin</option>
                                        <option value="sakit">Sakit</option>
                                    </select>
                                </div>

                                <div class="sm:col-span-2 md:col-span-1">
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Keterangan (Opsional)</label>
                                    <input type="text" name="keterangan" placeholder="Contoh: Bekerja di Divisi IT" class="block w-full rounded-xl border-gray-300 text-xs shadow-xs focus:border-uin focus:ring-uin py-2.5">
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:justify-end pt-3 border-t border-gray-100 gap-2">
                                <button type="submit" :disabled="!gpsReady" class="inline-flex items-center justify-center px-6 py-3 sm:py-2.5 border border-transparent text-xs font-bold rounded-xl text-white bg-uin hover:bg-uin-hover disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-md active:scale-95 w-full sm:w-auto">
                                    <svg class="w-4 h-4 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span x-text="type === 'pulang' ? 'Kirim Presensi Pulang (Check-Out)' : 'Kirim Presensi Masuk (Check-In)'"></span>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Riwayat Presensi -->
            <div class="bg-white shadow-xs rounded-xl sm:rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Riwayat Presensi & Koordinat GPS Anda</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Daftar kehadiran Masuk & Pulang Anda selama masa magang dengan titik koordinat peta.</p>
                    </div>
                    <div class="flex items-center justify-between sm:justify-end gap-2.5 w-full sm:w-auto">
                        <a href="{{ route('mahasiswa.presensi.cetak') }}" target="_blank" class="inline-flex items-center justify-center px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-xl text-xs font-bold shadow-xs hover:shadow transition flex-1 sm:flex-initial" title="Cetak / Unduh Berkas Rekap Presensi Resmi Format PDF">
                            <svg class="w-3.5 h-3.5 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>Cetak (PDF)</span>
                        </a>
                        <span class="text-xs font-bold px-3 py-2 bg-gray-200/80 text-gray-700 rounded-xl whitespace-nowrap shrink-0">
                            Total: {{ $presensi->count() }} Presensi
                        </span>
                    </div>
                </div>

                <!-- Tampilan Mobile (Card List) -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @forelse($presensi as $item)
                        <div class="p-4 space-y-2.5 bg-white hover:bg-gray-50/80 transition-colors">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="font-bold text-gray-900 text-xs sm:text-sm">
                                        {{ \Carbon\Carbon::parse($item->date)->translatedFormat('l, d M Y') }}
                                    </div>
                                    <div class="text-[11px] text-gray-400 font-medium">
                                        Pukul {{ $item->created_at->format('H:i:s') }} WIB
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <span class="px-2 py-0.5 rounded font-bold uppercase text-[10px] {{ $item->type == 'masuk' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                        {{ $item->type ?? 'Masuk' }}
                                    </span>
                                    @if($item->status == 'hadir')
                                        <span class="px-2 py-0.5 text-[10px] font-bold text-green-700 bg-green-50 border border-green-200 rounded-full">HADIR</span>
                                    @elseif($item->status == 'izin')
                                        <span class="px-2 py-0.5 text-[10px] font-bold text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-full">IZIN</span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] font-bold text-red-700 bg-red-50 border border-red-200 rounded-full">SAKIT</span>
                                    @endif
                                </div>
                            </div>

                            @if($item->keterangan)
                                <div class="text-xs text-gray-600 bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                    <span class="font-semibold text-gray-500 text-[10px] uppercase block mb-0.5">Keterangan:</span>
                                    {{ $item->keterangan }}
                                </div>
                            @endif

                            <div class="flex items-center justify-between pt-1 text-xs">
                                @if($item->latitude && $item->longitude)
                                    <div class="flex items-center gap-1.5">
                                        <button type="button" @click="openHistoryMap({{ $item->latitude }}, {{ $item->longitude }}, '{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }} - {{ strtoupper($item->type ?? 'Masuk') }}')" class="inline-flex items-center px-2.5 py-1.5 text-[11px] font-semibold text-uin bg-uin-light rounded-lg hover:bg-uin/20 transition-colors border border-uin/20 active:scale-95">
                                            <svg class="w-3.5 h-3.5 mr-1 text-uin shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            Lihat Peta
                                        </button>
                                        <a href="https://maps.google.com/?q={{ $item->latitude }},{{ $item->longitude }}" target="_blank" title="Buka di Google Maps" class="inline-flex items-center text-gray-500 hover:text-blue-600 transition-colors px-2 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 text-[11px]">
                                            <svg class="w-3.5 h-3.5 mr-1 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            G-Maps
                                        </a>
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-mono text-right">
                                        {{ number_format((float)$item->latitude, 4) }}, {{ number_format((float)$item->longitude, 4) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Tanpa koordinat GPS</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400 text-xs">
                            Belum ada riwayat presensi yang dicatat.
                        </div>
                    @endforelse
                </div>

                <!-- Tampilan Desktop (Tabel) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-xs text-left text-gray-600">
                        <thead class="text-[11px] text-gray-700 uppercase bg-gray-50 border-b border-gray-200 tracking-wider">
                            <tr>
                                <th class="px-5 py-3 font-semibold">Tanggal & Waktu</th>
                                <th class="px-5 py-3 font-semibold text-center">Tipe</th>
                                <th class="px-5 py-3 font-semibold text-center">Status</th>
                                <th class="px-5 py-3 font-semibold">Titik GPS & Peta</th>
                                <th class="px-5 py-3 font-semibold">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($presensi as $item)
                                <tr class="bg-white hover:bg-gray-50 transition-colors">
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
                                    <td class="px-5 py-3">
                                        @if($item->latitude && $item->longitude)
                                            <div class="flex items-center space-x-2">
                                                <button type="button" @click="openHistoryMap({{ $item->latitude }}, {{ $item->longitude }}, '{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }} - {{ strtoupper($item->type ?? 'Masuk') }}')" class="inline-flex items-center px-2 py-1 text-[11px] font-semibold text-uin bg-uin-light rounded-md hover:bg-uin/20 transition-colors border border-uin/20 active:scale-95">
                                                    <svg class="w-3.5 h-3.5 mr-1 text-uin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    Lihat di Peta
                                                </button>
                                                <a href="https://maps.google.com/?q={{ $item->latitude }},{{ $item->longitude }}" target="_blank" title="Buka di Google Maps" class="text-gray-400 hover:text-blue-600 transition-colors p-1 rounded hover:bg-gray-100">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                </a>
                                            </div>
                                            <span class="text-[10px] text-gray-400 font-mono block mt-1">
                                                {{ number_format((float)$item->latitude, 5) }}, {{ number_format((float)$item->longitude, 5) }}
                                            </span>
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

        <!-- MODAL DETAIL PETA RIWAYAT PRESENSI -->
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity" @click="closeHistoryMap()"></div>
            <div class="flex min-h-full items-center justify-center p-3 sm:p-4">
                <div class="relative bg-white rounded-2xl max-w-xl w-full p-4 sm:p-6 shadow-2xl border border-gray-100 z-10" @click.stop>
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <div class="flex items-center space-x-2 min-w-0">
                            <div class="p-2 bg-uin-light text-uin rounded-xl shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-sm sm:text-base font-bold text-gray-900 leading-tight">Titik Koordinat Presensi</h4>
                                <p class="text-xs text-gray-500 truncate" x-text="modalTitle"></p>
                            </div>
                        </div>
                        <button type="button" @click="closeHistoryMap()" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="mt-4">
                        <div id="modal-map" class="w-full bg-slate-100 rounded-xl overflow-hidden border border-gray-200"></div>
                    </div>

                    <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-3 border-t border-gray-100">
                        <span class="text-xs font-mono text-gray-600 font-semibold break-all" x-text="modalCoords"></span>
                        <button type="button" @click="closeHistoryMap()" class="w-full sm:w-auto px-4 py-2 bg-gray-100 text-gray-700 text-xs font-bold rounded-xl hover:bg-gray-200 transition-colors active:scale-95">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>