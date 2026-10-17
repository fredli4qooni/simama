<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Log Book Kegiatan Magang - {{ $user->name ?? 'Mahasiswa' }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10.5pt;
            line-height: 1.35;
            color: #000;
        }
        .header {
            text-align: center;
            border-bottom: 2.5px solid #000;
            padding-bottom: 6px;
            margin-bottom: 14px;
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
            margin: 12px 0 14px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 10pt;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .logbook-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 20px 0;
            font-size: 9.5pt;
        }
        .logbook-table th, .logbook-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: top;
        }
        .logbook-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            font-size: 9.5pt;
        }
        .logbook-table tr {
            page-break-inside: avoid;
        }
        .signature-box {
            width: 100%;
            margin-top: 20px;
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
            font-size: 10pt;
        }
        .signature-space {
            height: 55px;
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

    <!-- JUDUL LOG BOOK -->
    <div class="title">LOG BOOK KEGIATAN MAGANG</div>

    <!-- IDENTITAS MAHASISWA & MAGANG SESUAI FORMAT RESMI -->
    <table class="meta-table">
        <tr>
            <td style="width: 27%;">Nama Mahasiswa</td>
            <td style="width: 3%;">:</td>
            <td style="width: 70%; font-weight: bold;">{{ $user->name }}</td>
        </tr>
        <tr>
            <td>NPM</td>
            <td>:</td>
            <td>{{ $user->nim ?? '-' }}</td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>:</td>
            <td>{{ $user->prodi->name ?? 'Sistem Informasi' }}</td>
        </tr>
        <tr>
            <td>Lokasi Magang</td>
            <td>:</td>
            <td>
                {{ $pengajuan->instansi }}
                @if(!empty($pengajuan->alamat_instansi) || !empty($pengajuan->alamat))
                    - {{ $pengajuan->alamat_instansi ?? $pengajuan->alamat }}
                @endif
            </td>
        </tr>
        <tr>
            <td>Waktu Pelaksanaan Magang</td>
            <td>:</td>
            <td>
                @if($pengajuan->start_date && $pengajuan->end_date)
                    {{ \Carbon\Carbon::parse($pengajuan->start_date)->isoFormat('D MMMM Y') }} s.d {{ \Carbon\Carbon::parse($pengajuan->end_date)->isoFormat('D MMMM Y') }}
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td>Dosen Pembimbing Lapangan</td>
            <td>:</td>
            <td>
                {{ $pengajuan->dplAssignment->dpl->name ?? 'Belum Ditetapkan' }}
                @if(!empty($pengajuan->dplAssignment->dpl->nip))
                    (NIP. {{ $pengajuan->dplAssignment->dpl->nip }})
                @endif
            </td>
        </tr>
    </table>

    <!-- TABEL LOG BOOK SESUAI FORMAT RESMI FAKULTAS -->
    <table class="logbook-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 18%;">Hari/Tanggal</th>
                <th style="width: 37%;">Kegiatan</th>
                <th style="width: 25%;">Dokumentasi</th>
                <th style="width: 15%;">Paraf Mentor /<br>Pembimbing Instansi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logbooks as $index => $log)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">
                        <strong>{{ \Carbon\Carbon::parse($log->date)->isoFormat('dddd') }}</strong><br>
                        {{ \Carbon\Carbon::parse($log->date)->isoFormat('D MMMM Y') }}
                    </td>
                    <td>
                        <div style="font-weight: bold; margin-bottom: 2px;">Uraian Aktivitas:</div>
                        <div>{{ $log->kegiatan }}</div>
                        @if(!empty($log->feedback_dpl))
                            <div style="margin-top: 4px; padding-top: 3px; border-top: 0.5px dashed #777; font-size: 8.5pt; color: #333; font-style: italic;">
                                <strong>Catatan DPL:</strong> "{{ $log->feedback_dpl }}"
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: bold; margin-bottom: 2px;">Hasil / Luaran:</div>
                        <div>{{ $log->output ?? '-' }}</div>
                        <div style="margin-top: 4px; font-size: 8pt; color: #555; font-style: italic;">
                            (Dokumentasi foto terlampir pada Laporan Akhir)
                        </div>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <!-- Kolom Paraf Mentor Pembimbing Instansi -->
                        <span style="color: #bbb; font-size: 8pt;">[ Paraf ]</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; font-style: italic; color: #555;">
                        Belum ada catatan logbook kegiatan harian yang terekam.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN FORMAT RESMI FAKULTAS -->
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
                    <p style="margin: 0;">Bandar Lampung, {{ \Carbon\Carbon::parse($pengajuan->end_date ?? now())->isoFormat('D MMMM Y') }}</p>
                    <p style="margin: 0;">Mentor / Pembimbing Instansi</p>
                    <div class="signature-space"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                        ( ________________________ )
                    </p>
                    <p style="margin: 0;">
                        NIP/NIK. ....................................
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
