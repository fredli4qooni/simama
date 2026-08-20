<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas DPL - {{ $assignment->dpl->name }}</title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12pt; 
            line-height: 1.5; 
            margin: 2cm; 
        }
        .kop-surat { 
            text-align: center; 
            border-bottom: 3px solid black; 
            padding-bottom: 10px; 
            margin-bottom: 20px; 
        }
        .kop-surat h1 { font-size: 16pt; margin: 0; font-weight: bold; }
        .kop-surat h2 { font-size: 14pt; margin: 0; font-weight: bold; }
        .kop-surat p { font-size: 10pt; margin: 0; }
        .judul-surat { 
            text-align: center; 
            text-decoration: underline; 
            font-weight: bold; 
            font-size: 14pt; 
            margin-top: 20px; 
        }
        .nomor-surat { 
            text-align: center; 
            margin-bottom: 30px; 
        }
        .content { margin-top: 20px; text-align: justify; }
        .table-data { 
            width: 100%; 
            margin-left: 20px; 
            margin-top: 10px; 
            margin-bottom: 10px; 
        }
        .table-data td { padding: 3px; vertical-align: top; }
        .table-data td:first-child { width: 160px; }
        .table-anggota {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 15px 0;
        }
        .table-anggota th, .table-anggota td {
            border: 1px solid #000;
            padding: 5px 8px;
            font-size: 11pt;
        }
        .table-anggota th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .signature { 
            margin-top: 40px; 
            width: 300px; 
            float: right; 
            text-align: left; 
        }
        .clear { clear: both; }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h2>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h2>
        <h1>UNIVERSITAS ISLAM NEGERI RADEN INTAN LAMPUNG</h1>
        <h2>FAKULTAS SAINS DAN TEKNOLOGI</h2>
        <p>Jl. Letkol H. Endro Suratmin, Sukarame, Bandar Lampung, 35131</p>
    </div>

    <div class="judul-surat">SURAT TUGAS</div>
    <div class="nomor-surat">Nomor: B-..../Un.16/FST/PP.00.9/{{ date('m/Y') }}</div>

    <div class="content">
        <p>Dekan Fakultas Sains dan Teknologi Universitas Islam Negeri Raden Intan Lampung, dengan ini menugaskan kepada:</p>

        <table class="table-data">
            <tr>
                <td>Nama</td>
                <td>: <b>{{ $assignment->dpl->name }}</b></td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: {{ $assignment->dpl->email }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: Dosen Pembimbing Lapangan (DPL)</td>
            </tr>
        </table>

        <p>Untuk melaksanakan tugas pembimbingan mahasiswa program Praktik Kerja Lapangan (PKL) / Magang pada <b>{{ $assignment->application->instansi }}</b> bagi mahasiswa berikut:</p>

        <table class="table-anggota">
            <thead>
                <tr>
                    <th style="width: 8%;">No</th>
                    <th style="width: 42%;">Nama Mahasiswa</th>
                    <th style="width: 25%;">NIM</th>
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
                        <td style="text-align: center;">Pemohon</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <p>Demikian surat tugas ini dibuat untuk dapat dilaksanakan dengan sebaik-baiknya dan penuh tanggung jawab.</p>
    </div>

    <div class="signature">
        <p>Bandar Lampung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p>A.n. Dekan,<br>Ketua Program Studi</p>
        <br><br><br><br>
        <p><b>..........................................</b><br>NIP. ..........................................</p>
    </div>
    
    <div class="clear"></div>

</body>
</html>