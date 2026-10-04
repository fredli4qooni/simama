<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengantar Magang - {{ $pengajuan->user->name ?? 'Mahasiswa' }}</title>
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
            border-bottom: 2.5px solid #000;
            padding-bottom: 8px;
            margin-bottom: 20px;
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
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-table td {
            vertical-align: top;
        }
        .table-peserta {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .table-peserta th, .table-peserta td {
            border: 1px solid #000;
            padding: 6px 10px;
            font-size: 11pt;
        }
        .table-peserta th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .signature-box {
            width: 100%;
            margin-top: 35px;
        }
        .signature-col {
            width: 48%;
            float: right;
            text-align: left;
        }
        .signature-space {
            height: 70px;
        }
        .notice {
            margin-top: 30px;
            font-size: 9.5pt;
            font-style: italic;
            border: 1px dashed #777;
            padding: 8px;
            background-color: #fafafa;
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

    <table class="meta-table">
        <tr>
            <td style="width: 15%;">Nomor</td>
            <td style="width: 3%;">:</td>
            <td>B-...../Un.16/FST/SI/PP.00.9/{{ date('m/Y') }}</td>
            <td style="text-align: right;">Bandar Lampung, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>1 (Satu) Berkas Proposal</td>
            <td></td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td><strong>Permohonan Izin Magang / Kerja Praktik</strong></td>
            <td></td>
        </tr>
    </table>

    <p style="margin-bottom: 0;">Kepada Yth.<br>
    <strong>Pimpinan {{ $pengajuan->instansi }}</strong><br>
    di {{ $pengajuan->alamat ?? $pengajuan->alamat_instansi }}</p>

    <p style="margin-top: 15px;"><i>Assalamu'alaikum Wr. Wb.</i></p>
    <p style="text-align: justify; text-indent: 30px;">
        Dalam rangka implementasi kurikulum merdeka dan pembekalan kompetensi profesional bagi mahasiswa Program Studi Sistem Informasi Fakultas Sains dan Teknologi UIN Raden Intan Lampung, kami mengajukan permohonan izin pelaksanaan kegiatan Magang / Praktik Kerja Lapangan (PKL) bagi mahasiswa kami pada instansi/perusahaan yang Bapak/Ibu pimpin untuk periode <strong>{{ \Carbon\Carbon::parse($pengajuan->start_date)->isoFormat('D MMMM Y') }}</strong> sampai dengan <strong>{{ \Carbon\Carbon::parse($pengajuan->end_date)->isoFormat('D MMMM Y') }}</strong>.
    </p>

    <p>Adapun data mahasiswa pemohon adalah sebagai berikut:</p>

    <table class="table-peserta">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 37%;">Nama Mahasiswa</th>
                <th style="width: 25%;">NPM / NIM</th>
                <th style="width: 30%;">Status / Peran</th>
            </tr>
        </thead>
        <tbody>
            @if($pengajuan->members && $pengajuan->members->count() > 0)
                @foreach($pengajuan->members as $idx => $member)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td>{{ $member->user->name ?? '-' }}</td>
                        <td style="text-align: center;">{{ $member->user->nim ?? '-' }}</td>
                        <td style="text-align: center;">{{ $member->is_leader ? 'Ketua Kelompok' : 'Anggota Kelompok' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td style="text-align: center;">1</td>
                    <td>{{ $pengajuan->user->name ?? '-' }}</td>
                    <td style="text-align: center;">{{ $pengajuan->user->nim ?? '-' }}</td>
                    <td style="text-align: center;">Pemohon Tunggal</td>
                </tr>
            @endif
        </tbody>
    </table>

    <p style="text-align: justify;">
        Besar harapan kami agar Bapak/Ibu berkenan memberikan kesempatan bagi mahasiswa tersebut guna menggali pengalaman serta menerapkan ilmu keahlian di bidang Sistem Informasi. Atas perhatian, kesediaan, dan kerja sama yang baik, kami haturkan terima kasih.
    </p>
    <p><i>Wassalamu'alaikum Wr. Wb.</i></p>

    <div class="signature-box">
        <div class="signature-col">
            <p style="margin-bottom: 5px;">Mengetahui,<br>Ketua Program Studi Sistem Informasi</p>
            <div class="signature-space">
                <!-- Ruang Kosong untuk Tanda Tangan Manual & Stempel Basah -->
            </div>
            <p style="margin: 0; font-weight: bold; text-decoration: underline;">Fiqih Satria, M.T.I.</p>
            <p style="margin: 0;">NIP. 199211102019031016</p>
        </div>
    </div>

    <div style="clear: both;"></div>

    <div class="notice">
        <strong>Petunjuk Mahasiswa:</strong> Surat pengantar ini digenerate secara otomatis oleh sistem SIMAMA. Silakan cetak format dokumen ini lalu mintakan tanda tangan resmi dan stempel basah kepada Program Studi Sistem Informasi sebelum diserahkan ke instansi mitra.
    </div>
</body>
</html>
