<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pernyataan Kredit - Koperasi Karyawan Poliwangi</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }
        . {
            width: 700px; 
            border: 1px solid #ccc;
        }
        .header {
            text-align: center;
            border-bottom: 3px double black;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 16pt;
            margin: 0;
        }
        .header p {
            font-size: 10pt;
            margin: 2px 0;
        }
        .title-bar {
            background-color: #E0E0E0;
            text-align: center;
            font-weight: bold;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 5px;
            font-size: 11pt;
            border: 1px solid #BDBDBD;
        }
        .form-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .form-table td {
            padding: 2px 0;
        }
        .form-table .label {
            width: 25%;
        }
        .form-table .colon {
            width: 5%;
        }
        ol {
            padding-left: 20px;
            text-align: justify;
        }
        .justify {
            text-align: justify;
        }
        .date {
            text-align: right;
            margin-top: 30px;
        }
        .signature-table {
            width: 100%;
            margin-top: 20px;
            text-align: center;
        }
        .signature-table td {
            width: 33.33%;
            padding: 5px;
        }
    </style>
</head>
<body>

@php
    $user = auth()->user();
@endphp

<div class="container">

    <div class="header">
        <h1><strong>KOPERASI KARYAWAN</strong></h1>
        <h1><strong>POLITEKNIK NEGERI BANYUWANGI</strong></h1>
        <p>Jalan Raya Jember Km. 13 Labanasem, Kabat, Banyuwangi (68461)</p>
        <p>Telepon / Fax : (0333) 636780</p>
        <p>E-mail : poliwangi@poliwangi.ac.id Web Site : http://www.poliwangi.ac.id</p>
    </div>

    <div class="title-bar">DATA PEMOHON KREDIT</div>

    <table class="form-table">
        <tr><td class="label">Nama Pemohon</td><td class="colon">:</td><td>{{ $user->nama }}</td></tr>
        <tr><td class="label">NIP/NIK/NIPPPK</td><td class="colon">:</td><td>{{ $user->nip }}</td></tr>
        <tr><td class="label">Unit Kerja</td><td class="colon">:</td><td>{{ $user->unit_kerja }}</td></tr>
        <tr><td class="label">Alamat Rumah</td><td class="colon">:</td><td>{{ $user->alamat }}</td></tr>
        <tr><td class="label">Jumlah Pinjaman</td><td class="colon">:</td><td>{{ $user->jumlah_pinjaman }}</td></tr>
        <tr><td class="label">Telepon</td><td class="colon">:</td><td>{{ $user->telepon }}</td></tr>
        <tr><td class="label">Nama Istri/Suami</td><td class="colon">:</td><td>{{ $user->nama_pasangan }}</td></tr>
        <tr><td class="label">Status SK Kerja</td><td class="colon">:</td><td>{{ $user->status_sk }}</td></tr>
    </table>

    <div class="title-bar">SURAT PERNYATAAN DAN KUASA</div>

    <p class="justify">Yang bertanda tangan dibawah ini Saya:</p>
    <table class="form-table">
        <tr><td class="label">Nama Pemohon</td><td class="colon">:</td><td>{{ $user->nama }}</td></tr>
        <tr><td class="label">NIP/NIK/NIPPPK</td><td class="colon">:</td><td>{{ $user->nip }}</td></tr>
        <tr><td class="label">Unit Kerja</td><td class="colon">:</td><td>{{ $user->unit_kerja }}</td></tr>
        <tr><td class="label">Alamat Rumah</td><td class="colon">:</td><td>{{ $user->alamat }}</td></tr>
        <tr><td class="label">Telepon</td><td class="colon">:</td><td>{{ $user->telepon }}</td></tr>
    </table>

    <p class="justify">Menyatakan menerima ketentuan pinjaman sebagaimana tertulis ini, selanjutnya pinjaman tersebut akan menjadi pinjaman pribadi saya.</p>

    <ol>
        <li>Pinjaman / Kredit tersebut akan diangsur dalam jangka waktu 10 bulan (wajib diisi oleh pemohon)</li>
        <li>Atas pinjaman / kredit tersebut saya bersedia dikenakan bunga (tetap) 1 (satu) %/bulan.</li>
        <li>Untuk pelunasan fasilitas kredit tersebut, Saya memberikan kuasa penuh yang tidak dapat dibatalkan / dicabut kepada Bendahara KKP guna mendebet gaji / tunjangan lainnya yang selanjutnya dibayarkan sebagai angsuran bulanan / pelunasan kredit yang saya peroleh dari KKP.</li>
        <li>Apabila Saya keluar dari keanggotaan koperasi, maka KKP berhak untuk melakukan pendebetan sebesar jumlah tunggakan yang menjadi kewajiban Saya, langsung dari gaji / benefit terakhir yang menjadi hak Saya sebagai anggota.</li>
        <li>Apabila seluruh kompensasi tunjangan masih belum mencukupi pelunasan kredit, Saya bersedia melunasi sisa kredit pinjaman di atas baik secara tunai atau dengan harta milik pribadi maupun keluarga.</li>
    </ol>

    <p class="justify">Apabila Saya melalaikan hal-hal yang telah Saya sebutkan diatas, maka Saya bersedia dituntut sesuai dengan hukum yang berlaku.</p>
    <p class="justify">Demikian surat pernyataan dari kuasa ini Saya buat dengan sebenar-benarnya dan digunakan sebagaimana mestinya.</p>

    <div class="date">
        Banyuwangi, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </div>

    <table class="signature-table">
        <tr>
            <td>Yang memberi pernyataan & kuasa</td>
            <td>Mengetahui<br>Bendahara Koperasi</td>
            <td>Disetujui oleh<br>Ketua Koperasi</td>
        </tr>
        <tr>
            <td style="height: 70px;"></td>
            <td style="height: 70px;"></td>
            <td style="height: 70px;"></td>
        </tr>
        <tr>
            <td><strong>(<u>{{ $user->nama }}</u>)</strong></td>
            <td><strong>(<u>Erlina Kusumawati, S.ST.</u>)</strong></td>
            <td><strong>(<u>Dr. Danang S.W.P.J Widakdo, S.P., M.M</u>)</strong></td>
        </tr>
    </table>

</div>

</body>
</html>
