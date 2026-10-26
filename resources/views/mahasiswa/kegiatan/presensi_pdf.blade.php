<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Presensi Kehadiran Magang - {{ $user->name ?? 'Mahasiswa' }}</title>
    <style>
        @page {
            margin: 18mm 15mm 18mm 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            line-height: 1.35;
            color: #000;
        }
        .header {
            text-align: center;
            border-bottom: 2.5px solid #000;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .header h1 {
            margin: 0;
            font-size: 12.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 1px 0;
            font-size: 13.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 1px 0;
            font-size: 11.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0 0 0;
            font-size: 8.5pt;
            font-style: italic;
        }
        .title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0 12px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 9.5pt;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 2.5px 0;
            vertical-align: top;
        }
        .rekap-summary {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 6px 10px;
            margin-bottom: 12px;
            font-size: 9pt;
        }
        .presensi-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 16px 0;
            font-size: 9pt;
        }
        .presensi-table th, .presensi-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }
        .presensi-table th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
            font-size: 9pt;
            text-transform: uppercase;
        }
        .presensi-table tr {
            page-break-inside: avoid;
        }
        .badge {
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
        }
        .signature-box {
            width: 100%;
            margin-top: 15px;
            page-break-inside: avoid;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .signature-table td {
            border: none;
            vertical-align: top;
            font-size: 9.5pt;
        }
        .signature-space {
            height: 50px;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT RESMI FAKULTAS SAINS DAN TEKNOLOGI -->
    <div class="header">
        <h1>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h1>
        <h2>UNIVERSITAS ISLAM NEGERI RADEN INTAN LAMPUNG</h2>
        <h3>FAKULTAS SAINS DAN TEKNOLOGI</h3>
        <p>Alamat: Jl. Letkol H. Endro Suratmin Sukarame Bandar Lampung (35131) Telp. 0721-70360 email: saintek@radenintan.ac.id Website: saintek.radenintan.ac.id</p>
    </div>

    <!-- JUDUL REKAP PRESENSI -->
    <div class="title">REKAPITULASI PRESENSI KEHADIRAN & GPS MAGANG</div>

    <!-- IDENTITAS MAHASISWA & MAGANG -->
    <table class="meta-table">
        <tr>
            <td style="width: 25%;">Nama Mahasiswa</td>
            <td style="width: 2%;">:</td>
            <td style="width: 73%; font-weight: bold;">{{ $user->name }}</td>
        </tr>
        <tr>
            <td>NPM</td>
            <td>:</td>
            <td>{{ $user->nim ?? '-' }}</td>
        </tr>
        <tr>
            <td>Program Studi / Fakultas</td>
            <td>:</td>
            <td>S1 Sistem Informasi / Fakultas Sains dan Teknologi</td>
        </tr>
        <tr>
            <td>Instansi / Lokasi Magang</td>
            <td>:</td>
            <td>{{ $pengajuan->instansi }}{{ !empty($pengajuan->alamat) ? ' - ' . $pengajuan->alamat : '' }}</td>
        </tr>
        <tr>
            <td>Periode Pelaksanaan</td>
            <td>:</td>
            <td>
                {{ \Carbon\Carbon::parse($pengajuan->start_date)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($pengajuan->end_date)->translatedFormat('d F Y') }}
            </td>
        </tr>
        <tr>
            <td>Dosen Pembimbing (DPL)</td>
            <td>:</td>
            <td>{{ $pengajuan->dplAssignment->dpl->name ?? 'Belum Ditetapkan' }}</td>
        </tr>
    </table>

    <!-- RINGKASAN REKAPITULASI -->
    <table class="meta-table rekap-summary">
        <tr>
            <td style="width: 20%;"><strong>Total Presensi</strong>: {{ $presensi->count() }} kali</td>
            <td style="width: 20%;"><strong>Check-In (Masuk)</strong>: {{ $rekap['total_masuk'] ?? 0 }}</td>
            <td style="width: 20%;"><strong>Check-Out (Pulang)</strong>: {{ $rekap['total_pulang'] ?? 0 }}</td>
            <td style="width: 40%; text-align: right;">
                <strong>Hadir</strong>: {{ $rekap['hadir'] ?? 0 }} &nbsp;|&nbsp; 
                <strong>Izin</strong>: {{ $rekap['izin'] ?? 0 }} &nbsp;|&nbsp; 
                <strong>Sakit</strong>: {{ $rekap['sakit'] ?? 0 }}
            </td>
        </tr>
    </table>

    <!-- TABEL CATATAN PRESENSI LENGKAP -->
    <table class="presensi-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 18%;">Hari & Tanggal</th>
                <th style="width: 10%;">Waktu</th>
                <th style="width: 11%;">Tipe</th>
                <th style="width: 11%;">Status</th>
                <th style="width: 25%;">Titik GPS (Lat, Long)</th>
                <th style="width: 20%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presensi as $idx => $item)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($item->date)->translatedFormat('l, d M Y') }}
                    </td>
                    <td style="text-align: center; font-family: monospace;">
                        {{ $item->created_at->format('H:i:s') }} WIB
                    </td>
                    <td style="text-align: center;">
                        <span class="badge">{{ strtoupper($item->type ?? 'Masuk') }}</span>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge">{{ strtoupper($item->status ?? 'Hadir') }}</span>
                    </td>
                    <td style="font-family: monospace; font-size: 8pt;">
                        @if($item->latitude && $item->longitude)
                            {{ number_format((float)$item->latitude, 5) }}, {{ number_format((float)$item->longitude, 5) }}
                        @else
                            <span style="color: #888;">-</span>
                        @endif
                    </td>
                    <td>
                        {{ $item->keterangan ?: '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; font-style: italic; color: #555;">
                        Belum ada rekaman presensi kehadiran yang dicatat.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN PENGESAHAN FORMAT RESMI -->
    <div class="signature-box">
        <table class="signature-table">
            <tr>
                <td style="width: 50%; padding-right: 20px;">
                    <p style="margin: 0;">Mengetahui,</p>
                    <p style="margin: 0;">Dosen Pembimbing Lapangan (DPL)</p>
                    <div class="signature-space"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                        {{ $pengajuan->dplAssignment->dpl->name ?? '( ________________________ )' }}
                    </p>
                    <p style="margin: 0;">
                        NIP/NIDN. {{ $pengajuan->dplAssignment->dpl->nip ?? $pengajuan->dplAssignment->dpl->nim ?? '....................................' }}
                    </p>
                </td>
                <td style="width: 50%; padding-left: 20px;">
                    <p style="margin: 0;">Bandar Lampung, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
                    <p style="margin: 0;">Mahasiswa Yang Bersangkutan,</p>
                    <div class="signature-space"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                        {{ $user->name }}
                    </p>
                    <p style="margin: 0;">
                        NPM. {{ $user->nim ?? '-' }}
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
