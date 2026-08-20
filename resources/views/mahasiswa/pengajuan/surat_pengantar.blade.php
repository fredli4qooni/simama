<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengantar Magang</title>
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
        .notice {
            margin-top: 30px;
            font-size: 10pt;
            font-style: italic;
            border: 1px dashed #777;
            padding: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h2>
        <h3>PROGRAM STUDI {{ strtoupper($pengajuan->prodi->nama_prodi ?? 'TEKNOLOGI INFORMASI') }}</h3>
        <p style="margin: 0; font-size: 10pt;">Jl. Kampus Terpadu No. 1, Telp: (021) 1234567, Email: prodi@kampus.ac.id</p>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 15%;">Nomor</td>
            <td style="width: 3%;">:</td>
            <td>___/UN.PRODI/MAGANG/{{ date('Y') }}</td>
            <td style="text-align: right;">{{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td>-</td>
            <td></td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td>:</td>
            <td><strong>Permohonan Izin Magang / Kerja Praktik</strong></td>
            <td></td>
        </tr>
    </table>

    <p>Kepada Yth.<br>
    <strong>Pimpinan {{ $pengajuan->instansi }}</strong><br>
    {{ $pengajuan->alamat ?? $pengajuan->alamat_instansi }}</p>

    <p>Dengan hormat,</p>
    <p style="text-align: justify; text-indent: 30px;">
        Sehubungan dengan pelaksanaan kurikulum Program Studi {{ $pengajuan->prodi->nama_prodi ?? 'Teknologi Informasi' }}, kami bermaksud mengajukan permohonan izin pelaksanaan kegiatan Magang / Kerja Praktik bagi mahasiswa kami pada instansi/perusahaan yang Bapak/Ibu pimpin untuk periode <strong>{{ \Carbon\Carbon::parse($pengajuan->start_date)->isoFormat('D MMMM Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($pengajuan->end_date)->isoFormat('D MMMM Y') }}</strong>.
    </p>

    <p>Adapun daftar mahasiswa yang kami ajukan adalah sebagai berikut:</p>

    <table class="table-peserta">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 35%;">Nama Mahasiswa</th>
                <th style="width: 25%;">NIM</th>
                <th style="width: 32%;">Peran</th>
            </tr>
        </thead>
        <tbody>
            @if($pengajuan->members && $pengajuan->members->count() > 0)
                @foreach($pengajuan->members as $idx => $member)
                    <tr>
                        <td style="text-align: center;">{{ $idx + 1 }}</td>
                        <td>{{ $member->user->name ?? '-' }}</td>
                        <td style="text-align: center;">{{ $member->user->nim ?? '-' }}</td>
                        <td style="text-align: center;">{{ $member->is_leader ? 'Ketua Kelompok' : 'Anggota' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td style="text-align: center;">1</td>
                    <td>{{ $pengajuan->user->name ?? '-' }}</td>
                    <td style="text-align: center;">{{ $pengajuan->user->nim ?? '-' }}</td>
                    <td style="text-align: center;">Pemohon</td>
                </tr>
            @endif
        </tbody>
    </table>

    <p style="text-align: justify;">
        Demikian surat pengantar ini kami sampaikan. Besar harapan kami agar mahasiswa tersebut dapat diberikan kesempatan melaksanakan magang di tempat Bapak/Ibu. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.
    </p>

    <div class="signature-box">
        <div class="signature-col">
            <p style="margin-bottom: 5px;">Mengetahui,<br>Ketua Program Studi</p>
            <div class="signature-space">
                <!-- Ruang Kosong untuk Tanda Tangan Manual & Stempel Basah -->
            </div>
            <p style="margin: 0; font-weight: bold; text-decoration: underline;">( ____________________________ )</p>
            <p style="margin: 0;">NIP. ...................................................</p>
        </div>
    </div>

    <div style="clear: both;"></div>

    <div class="notice">
        <strong>Catatan untuk Mahasiswa:</strong> Surat pengantar ini digenerate secara otomatis oleh SIMAMA dalam format <em>belum bertanda tangan</em>. Silakan cetak dokumen ini dan mintakan tanda tangan resmi serta stempel basah kepada Program Studi sebelum diserahkan ke instansi.
    </div>
</body>
</html>
