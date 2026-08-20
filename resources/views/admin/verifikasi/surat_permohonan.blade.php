<!DOCTYPE html>
<html>
<head>
    <title>Surat Permohonan Magang</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; margin: 30px; font-size: 12pt; line-height: 1.5; }
        .kop-surat { text-align: center; border-bottom: 3px solid black; padding-bottom: 5px; margin-bottom: 20px; }
        .kop-surat h2 { margin: 0; font-size: 14pt; font-weight: bold; }
        .kop-surat h3 { margin: 0; font-size: 16pt; font-weight: bold; }
        .kop-surat p { margin: 0; font-size: 10pt; }
        .kanan { text-align: right; }
        table { width: 100%; border-collapse: collapse; }
        .ttd-box { margin-top: 50px; width: 300px; float: right; text-align: left; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h2>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h2>
        <h3>UNIVERSITAS ISLAM NEGERI RADEN INTAN LAMPUNG</h3>
        <p>Jl. Letkol H. Endro Suratmin, Sukarame, Bandar Lampung 35131</p>
    </div>
    
    <table>
        <tr>
            <td width="15%">Nomor</td><td width="3%">:</td><td width="42%">.../Un.16/FST/PP.00.9/{{ date('m/Y') }}</td>
            <td class="kanan" width="40%">Bandar Lampung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Lampiran</td><td>:</td><td colspan="2">-</td>
        </tr>
        <tr>
            <td>Perihal</td><td>:</td><td colspan="2"><b>Permohonan Izin Magang</b></td>
        </tr>
    </table>

    <div style="margin-top: 20px;">
        <p>Kepada Yth.,<br>
        <b>Pimpinan {{ $pengajuan->instansi }}</b><br>
        di Tempat</p>

        <p><i>Assalamu'alaikum Wr. Wb.</i></p>
        <p style="text-align: justify;">Sehubungan dengan pelaksanaan kurikulum pada Program Studi Sistem Informasi Fakultas Sains dan Teknologi UIN Raden Intan Lampung, kami memohon kesediaan Bapak/Ibu untuk dapat menerima mahasiswa kami guna melaksanakan kegiatan Magang/Praktik Kerja Lapangan (PKL) di instansi yang Bapak/Ibu pimpin.</p>
        
        <p>Adapun data mahasiswa tersebut adalah sebagai berikut:</p>
        <table style="margin-left: 20px; width: 90%;">
            <tr>
                <td width="25%">Nama</td><td width="5%">:</td><td><b>{{ $pengajuan->user->name }}</b></td>
            </tr>
            <tr>
                <td>NPM/NIM</td><td>:</td><td>{{ $pengajuan->user->nim ?? '-' }}</td>
            </tr>
            <tr>
                <td>Program Studi</td><td>:</td><td>Sistem Informasi</td>
            </tr>
        </table>

        <p style="text-align: justify;">Kegiatan magang ini direncanakan akan berlangsung mulai tanggal <b>{{ \Carbon\Carbon::parse($pengajuan->start_date)->translatedFormat('d F Y') }}</b> sampai dengan <b>{{ \Carbon\Carbon::parse($pengajuan->end_date)->translatedFormat('d F Y') }}</b>.</p>

        <p style="text-align: justify;">Demikian surat permohonan ini kami sampaikan. Atas perhatian dan kesediaan Bapak/Ibu, kami ucapkan terima kasih.</p>
        <p><i>Wassalamu'alaikum Wr. Wb.</i></p>
    </div>

    <div class="ttd-box">
        <p>Ketua Program Studi,<br>Sistem Informasi</p>
        <br><br><br>
        <p><b>( Nama Kaprodi )</b><br>NIP. .......................</p>
    </div>
</body>
</html>