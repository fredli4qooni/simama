<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Permohonan Izin Magang - {{ $pengajuan->user->name ?? 'Mahasiswa' }}</title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            margin: 30px 40px; 
            font-size: 12pt; 
            line-height: 1.5; 
            color: #000;
        }
        .kop-surat { 
            text-align: center; 
            border-bottom: 2.5px solid black; 
            padding-bottom: 8px; 
            margin-bottom: 20px; 
        }
        .kop-surat h1 { margin: 0; font-size: 13pt; font-weight: bold; text-transform: uppercase; }
        .kop-surat h2 { margin: 1px 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; }
        .kop-surat h3 { margin: 1px 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .kop-surat p { margin: 2px 0 0 0; font-size: 9pt; font-style: italic; }
        .kanan { text-align: right; }
        table { width: 100%; border-collapse: collapse; }
        .ttd-box { margin-top: 40px; width: 300px; float: right; text-align: left; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h1>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h1>
        <h2>UNIVERSITAS ISLAM NEGERI RADEN INTAN LAMPUNG</h2>
        <h3>FAKULTAS SAINS DAN TEKNOLOGI</h3>
        <p>Alamat: Jl. Letkol H. Endro Suratmin Sukarame Bandar Lampung (35131) Telp. 0721-70360 email: saintek@radenintan.ac.id</p>
    </div>
    
    <table>
        <tr>
            <td width="15%">Nomor</td>
            <td width="3%">:</td>
            <td width="42%">B-..../Un.16/WD1/PP.00.{{ date('d/m/Y') }}</td>
            <td class="kanan" width="40%">Bandar Lampung, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr>
            <td>Lampiran</td><td>:</td><td colspan="2">-</td>
        </tr>
        <tr>
            <td>Perihal</td><td>:</td><td colspan="2"><b>Permohonan Rekomendasi / Izin Magang</b></td>
        </tr>
    </table>

    <div style="margin-top: 20px;">
        <p>Kepada Yth.,<br>
        <b>Pimpinan {{ $pengajuan->instansi }}</b><br>
        di {{ $pengajuan->alamat ?? $pengajuan->alamat_instansi }}</p>

        <p><i>Assalamu'alaikum Wr. Wb.</i></p>
        <p style="text-align: justify; text-indent: 30px;">
            Sehubungan dengan pelaksanaan kurikulum pada Program Studi Sistem Informasi Fakultas Sains dan Teknologi UIN Raden Intan Lampung, kami mengajukan permohonan rekomendasi izin bagi mahasiswa kami untuk dapat melaksanakan kegiatan Magang / Praktik Kerja Lapangan (PKL) pada instansi/perusahaan yang Bapak/Ibu pimpin.
        </p>
        
        <p>Adapun identitas mahasiswa tersebut adalah sebagai berikut:</p>
        <table style="margin-left: 20px; width: 90%; margin-bottom: 15px;">
            <tr>
                <td width="28%">Nama Mahasiswa</td>
                <td width="3%">:</td>
                <td><b>{{ $pengajuan->user->name }}</b></td>
            </tr>
            <tr>
                <td>NPM / NIM</td>
                <td>:</td>
                <td>{{ $pengajuan->user->nim ?? '-' }}</td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>:</td>
                <td>Sistem Informasi</td>
            </tr>
            <tr>
                <td>Fakultas</td>
                <td>:</td>
                <td>Sains dan Teknologi</td>
            </tr>
        </table>

        <p style="text-align: justify;">
            Pelaksanaan kegiatan magang ini direncanakan akan berlangsung terhitung mulai tanggal <b>{{ \Carbon\Carbon::parse($pengajuan->start_date)->isoFormat('D MMMM Y') }}</b> sampai dengan <b>{{ \Carbon\Carbon::parse($pengajuan->end_date)->isoFormat('D MMMM Y') }}</b>.
        </p>

        <p style="text-align: justify;">
            Demikian surat permohonan ini kami sampaikan. Atas perhatian, perkenan, dan kerja sama yang baik dari Bapak/Ibu, kami ucapkan terima kasih.
        </p>
        <p><i>Wassalamu'alaikum Wr. Wb.</i></p>
    </div>

    <div class="ttd-box">
        <p>Ketua Program Studi Sistem Informasi,</p>
        <br><br><br>
        <p><b>Fiqih Satria, M.T.I.</b><br>NIP. 199211102019031016</p>
    </div>
</body>
</html>