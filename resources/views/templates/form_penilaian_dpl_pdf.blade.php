<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Penilaian Dosen Pembimbing Lapangan (DPL) - KKN Tematik Magang SI</title>
    <style>
        @page {
            margin: 20mm 15mm 15mm 15mm;
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
            font-size: 11.5pt;
            font-weight: bold;
            margin: 10px 0 2px 0;
            text-transform: uppercase;
        }
        .subtitle {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 10px;
            font-size: 10pt;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .petunjuk-box {
            border: 1px solid #555;
            padding: 6px 10px;
            margin-bottom: 10px;
            font-size: 9pt;
            background-color: #fafafa;
        }
        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 12px 0;
            font-size: 9.5pt;
        }
        .table-nilai th, .table-nilai td {
            border: 1px solid #000;
            padding: 5px 8px;
            vertical-align: middle;
        }
        .table-nilai th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .catatan-box {
            border: 1px solid #000;
            padding: 6px 10px;
            margin-top: 8px;
            min-height: 50px;
            font-size: 9.5pt;
        }
        .signature-box {
            width: 100%;
            margin-top: 15px;
            page-break-inside: avoid;
        }
        .signature-table {
            width: 100%;
            border: none;
        }
        .signature-table td {
            border: none;
            vertical-align: top;
            font-size: 10pt;
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

    <!-- JUDUL FORMULIR -->
    <div class="title">FORM PENILAIAN MAHASISWA KKN TEMATIK MAGANG</div>
    <div class="subtitle">OLEH DOSEN PEMBIMBING LAPANGAN (DPL)</div>

    <!-- IDENTITAS -->
    <table class="meta-table">
        <tr>
            <td style="width: 28%;">Program Studi</td>
            <td style="width: 2%;">:</td>
            <td style="width: 70%;">Sistem Informasi</td>
        </tr>
        <tr>
            <td>Nama Dosen Pembimbing (DPL)</td>
            <td>:</td>
            <td>{{ $dplName ?? '....................................................................................' }}</td>
        </tr>
        <tr>
            <td>NIP / NIDN</td>
            <td>:</td>
            <td>{{ $dplNip ?? '....................................................................................' }}</td>
        </tr>
        <tr>
            <td>Nama Mahasiswa</td>
            <td>:</td>
            <td>{{ $studentName ?? '....................................................................................' }}</td>
        </tr>
        <tr>
            <td>NPM</td>
            <td>:</td>
            <td>{{ $studentNim ?? '....................................................................................' }}</td>
        </tr>
        <tr>
            <td>Instansi / Lokasi Magang</td>
            <td>:</td>
            <td>{{ $instansiName ?? '....................................................................................' }}</td>
        </tr>
        <tr>
            <td>Periode Magang</td>
            <td>:</td>
            <td>{{ $periodeMagang ?? '....................................... s.d. .......................................' }}</td>
        </tr>
    </table>

    <!-- PETUNJUK PENILAIAN -->
    <div class="petunjuk-box">
        <strong>PETUNJUK PENILAIAN:</strong><br>
        Berikan penilaian pada setiap aspek dengan rentang skor <strong>1 – 100</strong>. Pedoman konversi:<br>
        86 – 100: Sangat Baik (A) &nbsp;|&nbsp; 76 – 85: Baik (B) &nbsp;|&nbsp; 66 – 75: Cukup (C) &nbsp;|&nbsp; 56 – 65: Kurang (D) &nbsp;|&nbsp; &le; 55: Sangat Kurang (E)
    </div>

    <!-- TABEL ASPEK PENILAIAN DPL -->
    <table class="table-nilai">
        <thead>
            <tr>
                <th style="width: 7%;">No</th>
                <th style="width: 73%;">Aspek yang Dinilai</th>
                <th style="width: 20%;">Nilai (1 – 100)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td>Kedisiplinan, komitmen, dan keaktifan konsultasi/bimbingan dengan DPL</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>Kualitas dan ketepatan waktu pengisian jurnal logbook kegiatan harian</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">3</td>
                <td>Relevansi dan penerapan keilmuan Sistem Informasi pada tempat magang</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">4</td>
                <td>Kualitas pelaksanaan program pengabdian / solusi teknologi yang dikembangkan</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">5</td>
                <td>Sistematika, tata tulis, dan kelengkapan bukti pada Laporan Akhir Magang</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">6</td>
                <td>Penguasaan materi, pemecahan masalah, integritas, dan etika akademik</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="2" style="text-align: right; padding-right: 15px;">RATA-RATA NILAI DPL (0 – 100):</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
        </tbody>
    </table>

    <div style="font-size: 9.5pt; font-weight: bold; margin-top: 5px;">CATATAN DAN EVALUASI DOSEN PEMBIMBING LAPANGAN:</div>
    <div class="catatan-box">
        ........................................................................................................................................................................................<br>
        ........................................................................................................................................................................................
    </div>

    <div style="margin-top: 8px; font-size: 9.5pt;">
        <strong>REKOMENDASI DPL:</strong> &nbsp;&nbsp;
        [&nbsp;&nbsp;] Sangat Direkomendasikan &nbsp;&nbsp;&nbsp;
        [&nbsp;&nbsp;] Direkomendasikan &nbsp;&nbsp;&nbsp;
        [&nbsp;&nbsp;] Cukup &nbsp;&nbsp;&nbsp;
        [&nbsp;&nbsp;] Perlu Perbaikan
    </div>

    <!-- TANDA TANGAN -->
    <div class="signature-box">
        <table class="signature-table">
            <tr>
                <td style="width: 55%;"></td>
                <td style="width: 45%; text-align: left;">
                    <p style="margin: 0;">Bandar Lampung, ............................................</p>
                    <p style="margin: 0;">Dosen Pembimbing Lapangan (DPL),</p>
                    <div class="signature-space"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                        {{ $dplName ?? '( ________________________________ )' }}
                    </p>
                    <p style="margin: 0;">
                        NIP/NIDN. {{ $dplNip ?? '................................................' }}
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
