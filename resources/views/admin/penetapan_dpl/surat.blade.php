<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas DPL - {{ $assignment->dpl->name }}</title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 11.5pt; 
            line-height: 1.45; 
            margin: 25px 35px; 
            color: #000;
        }
        .kop-surat { 
            text-align: center; 
            border-bottom: 2.5px solid black; 
            padding-bottom: 8px; 
            margin-bottom: 16px; 
        }
        .kop-surat h1 { font-size: 13pt; margin: 0; font-weight: bold; text-transform: uppercase; }
        .kop-surat h2 { font-size: 14pt; margin: 1px 0; font-weight: bold; text-transform: uppercase; }
        .kop-surat h3 { font-size: 12pt; margin: 1px 0; font-weight: bold; text-transform: uppercase; }
        .kop-surat p { font-size: 9pt; margin: 2px 0 0 0; font-style: italic; }
        .judul-surat { 
            text-align: center; 
            text-decoration: underline; 
            font-weight: bold; 
            font-size: 13pt; 
            margin-top: 15px; 
            text-transform: uppercase;
        }
        .nomor-surat { 
            text-align: center; 
            margin-bottom: 20px; 
            font-size: 10.5pt;
        }
        .content { margin-top: 15px; text-align: justify; }
        .table-data { 
            width: 100%; 
            margin-left: 20px; 
            margin-top: 8px; 
            margin-bottom: 12px; 
        }
        .table-data td { padding: 2.5px; vertical-align: top; }
        .table-data td:first-child { width: 180px; }
        .table-anggota {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 15px 0;
        }
        .table-anggota th, .table-anggota td {
            border: 1px solid #000;
            padding: 5px 8px;
            font-size: 10.5pt;
        }
        .table-anggota th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .signature-box { 
            width: 100%;
            margin-top: 30px; 
        }
        .signature-space {
            height: 60px;
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h1>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h1>
        <h2>UNIVERSITAS ISLAM NEGERI RADEN INTAN LAMPUNG</h2>
        <h3>FAKULTAS SAINS DAN TEKNOLOGI</h3>
        <p>Alamat: Jl. Letkol H. Endro Suratmin Sukarame Bandar Lampung (35131) Telp. 0721-70360 email: saintek@radenintan.ac.id</p>
    </div>

    <div class="judul-surat">SURAT TUGAS</div>
    <div class="nomor-surat">Nomor: B-...../Un.16/FST/PP.00.9/{{ date('m/Y') }}</div>

    <div class="content">
        <p>Dekan Fakultas Sains dan Teknologi Universitas Islam Negeri Raden Intan Lampung, dengan ini menugaskan kepada:</p>

        <table class="table-data">
            <tr>
                <td>Nama</td>
                <td>: <b>{{ $assignment->dpl->name }}</b></td>
            </tr>
            <tr>
                <td>NIP / NIDN</td>
                <td>: {{ $assignment->dpl->nim ?? '-' }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: {{ $assignment->dpl->email }}</td>
            </tr>
            <tr>
                <td>Tugas / Jabatan</td>
                <td>: Dosen Pembimbing Lapangan (DPL) Magang Mahasiswa</td>
            </tr>
        </table>

        <p>Untuk melaksanakan tugas pembimbingan, pemantauan, serta evaluasi akhir mahasiswa kegiatan Praktik Kerja Lapangan (PKL) / KKN Tematik Magang pada instansi <b>{{ $assignment->application->instansi }}</b> bagi mahasiswa berikut:</p>

        <table class="table-anggota">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th style="width: 42%;">Nama Mahasiswa</th>
                    <th style="width: 25%;">NPM / NIM</th>
                    <th style="width: 25%;">Peran</th>
                </tr>
            </thead>
            <tbody>
                @if($assignment->application->members && $assignment->application->members->count() > 0)
                    @foreach($assignment->application->members as $idx => $member)
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
                        <td>{{ $assignment->application->user->name ?? '-' }}</td>
                        <td style="text-align: center;">{{ $assignment->application->user->nim ?? '-' }}</td>
                        <td style="text-align: center;">Pemohon Tunggal</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <p>Demikian surat tugas ini dibuat untuk dapat dilaksanakan dengan penuh dedikasi dan rasa tanggung jawab.</p>
    </div>

    <div class="signature-box">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; vertical-align: top; font-size: 10.5pt;">
                    <p style="margin: 0;">Mengetahui,<br>Ketua Program Studi Sistem Informasi</p>
                    <div class="signature-space"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">Fiqih Satria, M.T.I.</p>
                    <p style="margin: 0;">NIP. 199211102019031016</p>
                </td>
                <td style="width: 50%; vertical-align: top; font-size: 10.5pt; text-align: left; padding-left: 20px;">
                    <p style="margin: 0;">Bandar Lampung, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br>Dekan Fakultas Sains dan Teknologi,</p>
                    <div class="signature-space"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">Dr. Sovia Mas Ayu, M.A.</p>
                    <p style="margin: 0;">NIP. 197611302005012006</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>