<!DOCTYPE html>
<html>
<head>
    <title>Rekapitulasi Nilai Magang</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 20px; font-size: 11pt; line-height: 1.5; }
        .kop-surat { text-align: center; border-bottom: 3px solid black; padding-bottom: 5px; margin-bottom: 20px; }
        .kop-surat h2 { margin: 0; font-size: 14pt; }
        .kop-surat h3 { margin: 0; font-size: 16pt; font-weight: bold; }
        .judul { text-align: center; font-weight: bold; font-size: 12pt; margin-bottom: 20px; text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; font-size: 10pt; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .ttd-box { margin-top: 40px; width: 300px; float: right; text-align: left; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h2>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h2>
        <h3>UNIVERSITAS ISLAM NEGERI RADEN INTAN LAMPUNG</h3>
        <p style="margin: 0; font-size: 10pt;">Fakultas Sains dan Teknologi - Program Studi Sistem Informasi</p>
    </div>

    <div class="judul">
        REKAPITULASI NILAI AKHIR MAGANG MAHASISWA
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Mahasiswa</th>
                <th width="12%">NIM</th>
                <th width="23%">Instansi Magang</th>
                <th width="20%">Dosen Pembimbing</th>
                <th width="10%">Nilai Akhir</th>
                <th width="10%">Huruf Mutu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuan as $index => $item)
                @php
                    $nilai = $item->evaluation->nilai_akhir ?? 0;
                    $huruf = 'E';
                    if ($nilai >= 85) $huruf = 'A';
                    elseif ($nilai >= 70) $huruf = 'B';
                    elseif ($nilai >= 55) $huruf = 'C';
                    elseif ($nilai >= 40) $huruf = 'D';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td class="text-center">{{ $item->user->nim }}</td>
                    <td>{{ $item->instansi }}</td>
                    <td>{{ $item->dplAssignment->dpl->name ?? '-' }}</td>
                    <td class="text-center"><b>{{ number_format($nilai, 2) }}</b></td>
                    <td class="text-center"><b>{{ $huruf }}</b></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd-box">
        <p>Bandar Lampung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
        Ketua Program Studi,</p>
        <br><br><br>
        <p><b>( Nama Kaprodi )</b><br>NIP. .......................</p>
    </div>
</body>
</html>