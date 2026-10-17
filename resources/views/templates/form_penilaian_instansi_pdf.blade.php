<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Penilaian Pembimbing Instansi Mitra - KKN Tematik Magang SI</title>
    <style>
        @page {
            margin: 18mm 15mm 15mm 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
        }
        .header {
            text-align: center;
            border-bottom: 2.5px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 1px 0;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 1px 0;
            font-size: 11pt;
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
            font-size: 11pt;
            font-weight: bold;
            margin: 8px 0 2px 0;
            text-transform: uppercase;
        }
        .subtitle {
            text-align: center;
            font-size: 9.5pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 8px;
            font-size: 9.5pt;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 1.5px 0;
            vertical-align: top;
        }
        .petunjuk-box {
            border: 1px solid #555;
            padding: 5px 8px;
            margin-bottom: 8px;
            font-size: 8.5pt;
            background-color: #fafafa;
        }
        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 10px 0;
            font-size: 9pt;
        }
        .table-nilai th, .table-nilai td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }
        .table-nilai th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .catatan-box {
            border: 1px solid #000;
            padding: 5px 8px;
            margin-top: 5px;
            min-height: 40px;
            font-size: 9pt;
        }
        .signature-box {
            width: 100%;
            margin-top: 10px;
            page-break-inside: avoid;
        }
        .signature-table {
            width: 100%;
            border: none;
        }
        .signature-table td {
            border: none;
            vertical-align: top;
            font-size: 9.5pt;
        }
        .signature-space {
            height: 45px;
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
    <div class="subtitle">OLEH PEMBIMBING INSTANSI MITRA</div>

    <!-- IDENTITAS -->
    <table class="meta-table">
        <tr>
            <td style="width: 28%;">Program Studi</td>
            <td style="width: 2%;">:</td>
            <td style="width: 70%;">Sistem Informasi</td>
        </tr>
        <tr>
            <td>Nama Instansi / Perusahaan</td>
            <td>:</td>
            <td>{{ $instansiName ?? '....................................................................................' }}</td>
        </tr>
        <tr>
            <td>Alamat Instansi / Perusahaan</td>
            <td>:</td>
            <td>{{ $instansiAddress ?? '....................................................................................' }}</td>
        </tr>
        <tr>
            <td>Nama Pembimbing Lapangan</td>
            <td>:</td>
            <td>....................................................................................</td>
        </tr>
        <tr>
            <td>Nama Mahasiswa</td>
            <td>:</td>
            <td>{{ $studentName ?? '....................................................................................' }}</td>
        </tr>
        <tr>
            <td>NPM / NIM</td>
            <td>:</td>
            <td>{{ $studentNim ?? '....................................................................................' }}</td>
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
        Berikan penilaian pada setiap aspek dengan rentang skor <strong>1 – 100</strong>.<br>
        86 – 100: Sangat Baik &nbsp;|&nbsp; 76 – 85: Baik &nbsp;|&nbsp; 66 – 75: Cukup &nbsp;|&nbsp; 56 – 65: Kurang &nbsp;|&nbsp; &le; 55: Sangat Kurang
    </div>

    <!-- TABEL 10 ASPEK PENILAIAN PEMBIMBING INSTANSI -->
    <table class="table-nilai">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 74%;">Aspek yang Dinilai</th>
                <th style="width: 20%;">Nilai (1 – 100)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td>Kehadiran dan Ketepatan Waktu</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>Disiplin dalam Melaksanakan Tugas yang Diberikan</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">3</td>
                <td>Integritas, Kejujuran, dan Tanggung Jawab Kerja</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">4</td>
                <td>Sikap, Etika, dan Sopan Santun di Lingkungan Kerja</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">5</td>
                <td>Kemampuan Bekerja Sama dalam Tim</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">6</td>
                <td>Kemampuan Komunikasi dan Penyampaian Pendapat</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">7</td>
                <td>Kemampuan Teknis Bidang Sistem Informasi / IT</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">8</td>
                <td>Kemampuan Menyelesaikan Masalah (Problem Solving)</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">9</td>
                <td>Kreativitas dan Inovasi dalam Pelaksanaan Pekerjaan</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">10</td>
                <td>Kontribusi terhadap Efektivitas Kerja Instansi</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="2" style="text-align: right; padding-right: 15px;">RATA-RATA NILAI PEMBIMBING INSTANSI (0 – 100):</td>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
        </tbody>
    </table>

    <div style="font-size: 9pt; font-weight: bold;">CATATAN DAN EVALUASI PEMBIMBING INSTANSI:</div>
    <div class="catatan-box">
        ........................................................................................................................................................................................
    </div>

    <div style="margin-top: 6px; font-size: 9pt;">
        <strong>REKOMENDASI PEMBIMBING INSTANSI:</strong> &nbsp;&nbsp;
        [&nbsp;&nbsp;] Sangat Direkomendasikan &nbsp;&nbsp;&nbsp;
        [&nbsp;&nbsp;] Direkomendasikan &nbsp;&nbsp;&nbsp;
        [&nbsp;&nbsp;] Cukup &nbsp;&nbsp;&nbsp;
        [&nbsp;&nbsp;] Tidak Direkomendasikan
    </div>

    <!-- TANDA TANGAN -->
    <div class="signature-box">
        <table class="signature-table">
            <tr>
                <td style="width: 55%;"></td>
                <td style="width: 45%; text-align: left;">
                    <p style="margin: 0;">Bandar Lampung, ............................................</p>
                    <p style="margin: 0;">Pembimbing Instansi / Mitra Lapangan,</p>
                    <div class="signature-space"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                        ( ________________________________ )
                    </p>
                    <p style="margin: 0;">
                        NIP / NIK. ................................................
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
