<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Penilaian Magang</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 30px 40px;
            color: #000;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 2px 0;
            font-size: 12pt;
            font-weight: normal;
        }
        .title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0 15px 0;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .table-nilai {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .table-nilai th, .table-nilai td {
            border: 1px solid #000;
            padding: 8px 12px;
            font-size: 11pt;
        }
        .table-nilai th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .signature-box {
            width: 100%;
            margin-top: 40px;
        }
        .signature-col {
            width: 45%;
            float: right;
            text-align: left;
        }
        .signature-space {
            height: 70px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h2>
        <h3>PROGRAM STUDI {{ strtoupper($application->prodi->nama_prodi ?? 'TEKNOLOGI INFORMASI') }}</h3>
        <p style="margin: 0; font-size: 10pt;">Jl. Kampus Terpadu No. 1, Telp: (021) 1234567, Email: prodi@kampus.ac.id</p>
    </div>

    <div class="title">LEMBAR PENILAIAN MAGANG / KERJA PRAKTIK</div>

    <table class="info-table">
        <tr>
            <td style="width: 25%;">Nama Mahasiswa / Ketua</td>
            <td style="width: 3%;">:</td>
            <td><strong>{{ $application->user->name ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>:</td>
            <td>{{ $application->user->nim ?? '-' }}</td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>:</td>
            <td>{{ $application->prodi->nama_prodi ?? 'Teknologi Informasi' }}</td>
        </tr>
        <tr>
            <td>Instansi Magang</td>
            <td>:</td>
            <td>{{ $application->instansi }}</td>
        </tr>
        <tr>
            <td>Periode Magang</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($application->start_date)->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($application->end_date)->isoFormat('D MMMM Y') }}</td>
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
                <th style="width: 10%;">No</th>
                <th style="width: 60%;">Komponen Penilaian</th>
                <th style="width: 30%;">Skor / Nilai (0 - 100)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td>Nilai Bimbingan Dosen Pembimbing Lapangan (DPL)</td>
                <td style="text-align: center; font-weight: bold;">{{ number_format($application->evaluation->nilai_dpl ?? 0, 2) }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>Nilai Evaluasi Instansi / Pembimbing Lapangan</td>
                <td style="text-align: center; font-weight: bold;">{{ number_format($application->evaluation->nilai_instansi ?? 0, 2) }}</td>
            </tr>
            <tr style="background-color: #f9f9f9;">
                <td colspan="2" style="text-align: right; font-weight: bold;">NILAI AKHIR RATA-RATA:</td>
                <td style="text-align: center; font-size: 13pt; font-weight: bold; color: #000;">
                    {{ number_format($application->evaluation->nilai_akhir ?? 0, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    @if($application->evaluation && $application->evaluation->komentar)
        <p><strong>Catatan / Evaluasi DPL:</strong><br>
        <em>"{{ $application->evaluation->komentar }}"</em></p>
    @endif

    <div class="signature-box">
        <div class="signature-col">
            <p style="margin-bottom: 5px;">{{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>Dosen Pembimbing Lapangan (DPL),</p>
            <div class="signature-space"></div>
            <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $application->dplAssignment->dpl->name ?? '( ________________________ )' }}</p>
            <p style="margin: 0;">NIDN/NIP. {{ $application->dplAssignment->dpl->nim ?? '....................................' }}</p>
        </div>
    </div>
</body>
</html>
