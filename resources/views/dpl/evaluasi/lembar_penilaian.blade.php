<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Penilaian KKN Tematik Magang - {{ $application->user->name ?? 'Mahasiswa' }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            margin: 25px 35px;
            color: #000;
        }
        .header {
            text-align: center;
            border-bottom: 2.5px solid #000;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }
        .header h1 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 1px 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 1px 0;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0 0 0;
            font-size: 9pt;
            font-style: italic;
        }
        .title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 14px 0 4px 0;
            text-transform: uppercase;
        }
        .subtitle {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 16px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 14px;
            font-size: 10.5pt;
        }
        .info-table td {
            padding: 2.5px 0;
            vertical-align: top;
        }
        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0;
        }
        .table-nilai th, .table-nilai td {
            border: 1px solid #000;
            padding: 6px 10px;
            font-size: 10.5pt;
        }
        .table-nilai th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .table-kriteria {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9pt;
        }
        .table-kriteria th, .table-kriteria td {
            border: 1px solid #777;
            padding: 3px 6px;
            text-align: center;
        }
        .table-kriteria th {
            background-color: #fafafa;
        }
        .signature-box {
            width: 100%;
            margin-top: 25px;
        }
        .signature-col {
            width: 48%;
            text-align: left;
            font-size: 10.5pt;
        }
        .signature-space {
            height: 60px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h1>
        <h2>UNIVERSITAS ISLAM NEGERI RADEN INTAN LAMPUNG</h2>
        <h3>FAKULTAS SAINS DAN TEKNOLOGI</h3>
        <p>Alamat: Jl. Letkol H. Endro Suratmin Sukarame Bandar Lampung (35131) Telp. 0721-70360 email: saintek@radenintan.ac.id</p>
    </div>

    <div class="title">LEMBAR PENILAIAN AKHIR KKN TEMATIK MAGANG</div>
    <div class="subtitle">PROGRAM STUDI SISTEM INFORMASI TAHUN {{ date('Y') }}</div>

    <table class="info-table">
        <tr>
            <td style="width: 28%;">Nama Mahasiswa</td>
            <td style="width: 3%;">:</td>
            <td><strong>{{ $application->user->name ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td>NPM / NIM</td>
            <td>:</td>
            <td>{{ $application->user->nim ?? '-' }}</td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>:</td>
            <td>Sistem Informasi</td>
        </tr>
        <tr>
            <td>Instansi / Lokasi Magang</td>
            <td>:</td>
            <td><strong>{{ $application->instansi }}</strong></td>
        </tr>
        <tr>
            <td>Jalur Penempatan</td>
            <td>:</td>
            <td>{{ $application->jenis_magang === 'mandiri' ? 'Magang Mandiri (Inisiatif Mitra Sendiri)' : 'Magang Program Studi (Reguler)' }}</td>
        </tr>
        <tr>
            <td>Periode Pelaksanaan</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($application->start_date)->isoFormat('D MMMM Y') }} s.d. {{ \Carbon\Carbon::parse($application->end_date)->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr>
            <td>Dosen Pembimbing (DPL)</td>
            <td>:</td>
            <td>{{ $application->dplAssignment->dpl->name ?? '-' }}</td>
        </tr>
    </table>

    <table class="table-nilai">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 54%;">Komponen Penilaian Terpadu</th>
                <th style="width: 18%;">Bobot</th>
                <th style="width: 20%;">Nilai (0 – 100)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td>
                    <strong>Nilai Pembimbing Instansi / Mitra Lapangan</strong><br>
                    <span style="font-size: 9pt; color: #444;">(Kedisiplinan, etika, kerjasama tim, kemampuan teknis SI & pemecahan masalah)</span>
                </td>
                <td style="text-align: center;">50%</td>
                <td style="text-align: center; font-weight: bold; font-size: 11pt;">
                    {{ number_format($application->evaluation->nilai_instansi ?? 0, 2) }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>
                    <strong>Nilai Dosen Pembimbing Lapangan (DPL)</strong><br>
                    <span style="font-size: 9pt; color: #444;">(Bimbingan akademis, jurnal logbook, pengabdian & laporan akhir magang)</span>
                </td>
                <td style="text-align: center;">50%</td>
                <td style="text-align: center; font-weight: bold; font-size: 11pt;">
                    {{ number_format($application->evaluation->nilai_dpl ?? 0, 2) }}
                </td>
            </tr>
            @php
                $na = $application->evaluation->nilai_akhir ?? 0;
                $huruf = $na >= 86 ? 'A' : ($na >= 76 ? 'B' : ($na >= 66 ? 'C' : ($na >= 56 ? 'D' : 'E')));
                $kriteria = $na >= 86 ? 'Sangat Baik' : ($na >= 76 ? 'Baik' : ($na >= 66 ? 'Cukup' : ($na >= 56 ? 'Kurang' : 'Sangat Kurang')));
            @endphp
            <tr style="background-color: #f9f9f9;">
                <td colspan="3" style="text-align: right; font-weight: bold; padding-right: 15px;">
                    NILAI AKHIR RATA-RATA:
                </td>
                <td style="text-align: center; font-size: 13pt; font-weight: bold;">
                    {{ number_format($na, 2) }}
                </td>
            </tr>
            <tr style="background-color: #fcfcfc;">
                <td colspan="3" style="text-align: right; font-weight: bold; padding-right: 15px;">
                    NILAI MUTU / PREDIKAT KELULUSAN:
                </td>
                <td style="text-align: center; font-weight: bold; font-size: 11pt;">
                    {{ $huruf }} ({{ $kriteria }})
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table-kriteria">
        <thead>
            <tr>
                <th colspan="5">Pedoman Konversi Nilai Fakultas Sains dan Teknologi UIN Raden Intan Lampung</th>
            </tr>
            <tr>
                <th>86 – 100</th>
                <th>76 – 85</th>
                <th>66 – 75</th>
                <th>56 – 65</th>
                <th>&le; 55</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>A (Sangat Baik)</td>
                <td>B (Baik)</td>
                <td>C (Cukup)</td>
                <td>D (Kurang)</td>
                <td>E (Sangat Kurang)</td>
            </tr>
        </tbody>
    </table>

    @if($application->evaluation && $application->evaluation->komentar)
        <div style="margin-top: 10px; border: 1px solid #ccc; padding: 6px 10px; background-color: #fafafa; font-size: 10pt;">
            <strong>Catatan & Evaluasi Pembimbing:</strong><br>
            <em>"{{ $application->evaluation->komentar }}"</em>
        </div>
    @endif

    <div class="signature-box">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; vertical-align: top; font-size: 10.5pt;">
                    <p style="margin: 0;">Mengetahui,<br>Ketua Program Studi Sistem Informasi</p>
                    <div class="signature-space"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">Fiqih Satria, M.T.I.</p>
                    <p style="margin: 0;">NIP. 199211102019031016</p>
                </td>
                <td style="width: 50%; vertical-align: top; font-size: 10.5pt; text-align: left; padding-left: 30px;">
                    <p style="margin: 0;">Bandar Lampung, {{ \Carbon\Carbon::parse($application->evaluation->published_at ?? now())->isoFormat('D MMMM Y') }}<br>Dosen Pembimbing Lapangan (DPL),</p>
                    <div class="signature-space"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $application->dplAssignment->dpl->name ?? '( ________________________ )' }}</p>
                    <p style="margin: 0;">NIDN/NIP. {{ $application->dplAssignment->dpl->nim ?? '....................................' }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
