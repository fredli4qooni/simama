<!DOCTYPE html>
<html>
<head>
    <title>Rekapitulasi Nilai Magang - Program Studi Sistem Informasi</title>
    <style>
        @page { margin: 1.5cm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 10.5pt; line-height: 1.4; color: #111; }
        .kop-surat { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 20px; }
        .kop-surat h2 { margin: 0; font-size: 12pt; font-weight: normal; letter-spacing: 0.5px; }
        .kop-surat h1 { margin: 2px 0; font-size: 14pt; font-weight: bold; }
        .kop-surat h3 { margin: 0; font-size: 12pt; font-weight: bold; }
        .kop-surat p { margin: 3px 0 0; font-size: 8.5pt; font-style: italic; }
        .judul { text-align: center; font-weight: bold; font-size: 11.5pt; margin-bottom: 18px; text-decoration: underline; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; font-size: 9.5pt; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .ttd-box { margin-top: 25px; width: 320px; float: right; text-align: left; font-size: 10pt; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h2>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h2>
        <h1>UNIVERSITAS ISLAM NEGERI RADEN INTAN LAMPUNG</h1>
        <h3>FAKULTAS SAINS DAN TEKNOLOGI</h3>
        <h3 style="font-size: 11pt; font-weight: bold; margin-top: 2px;">PROGRAM STUDI SISTEM INFORMASI</h3>
        <p>Jl. Letkol H. Endro Suratmin Sukarame Bandar Lampung (35131) Telp. 0721-70360, email: saintek@radenintan.ac.id</p>
    </div>

    <div class="judul">
        REKAPITULASI NILAI AKHIR MAGANG MAHASISWA PROGRAM STUDI SISTEM INFORMASI
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="22%">Nama Mahasiswa</th>
                <th width="14%">NPM</th>
                <th width="24%">Instansi Magang</th>
                <th width="21%">Dosen Pembimbing</th>
                <th width="7%">Nilai</th>
                <th width="7%">Mutu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuan as $index => $item)
                @php
                    $nilai = $item->evaluation->nilai_akhir ?? 0;
                    $huruf = 'E';
                    if ($nilai >= 86) $huruf = 'A';
                    elseif ($nilai >= 76) $huruf = 'B';
                    elseif ($nilai >= 66) $huruf = 'C';
                    elseif ($nilai >= 56) $huruf = 'D';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td class="text-center">{{ $item->user->nim ?? '-' }}</td>
                    <td>{{ $item->instansi }}</td>
                    <td>{{ $item->dplAssignment->dpl->name ?? '-' }}</td>
                    <td class="text-center"><b>{{ number_format($nilai, 1) }}</b></td>
                    <td class="text-center"><b>{{ $huruf }}</b></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd-box">
        <p>Bandar Lampung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
        Ketua Program Studi Sistem Informasi,</p>
        <br><br><br>
        <p><b>Fiqih Satria, M.T.I.</b><br>NIP. 199211102019031016</p>
    </div>
</body>
</html>