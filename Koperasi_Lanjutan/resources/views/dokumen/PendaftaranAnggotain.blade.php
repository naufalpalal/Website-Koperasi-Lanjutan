<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Formulir Pendaftaran Anggota</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
        }

        .container {
            width: 700px;
            margin: auto;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h3,
        .header h4 {
            margin: 2px 0;
        }

        .header p {
            margin: 2px 0;
            font-size: 10pt;
        }

        .title {
            text-align: center;
            font-weight: bold;
            margin: 30px 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table td {
            padding: 4px;
        }

        .content-list {
            padding-left: 20px;
        }

        .signature {
            margin-top: 50px;
        }

        .signature td {
            width: 50%;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container" style="position: relative;">
        <div class="header"
            style="text-align: center; border-bottom: 2px solid black; padding-bottom: 10px; margin-bottom: 20px;">

            <!-- Logo di kiri -->
            <img src="{{ public_path('assets/favicon.png') }}" alt="Logo"
                style="position: absolute; left: 0; top: 0; width: 106px; height: 106px;">

            <!-- Teks header di tengah -->
            <h3>KOPERASI KARYAWAN</h3>
            <h4>POLITEKNIK NEGERI BANYUWANGI</h4>
            <p>
                Jalan Raya Jember Km. 13 Labanasem, Kabat, Banyuwangi (68461) <br>
                Telepon/Fax: (0333) 636780 <br>
                E-mail: poliwangi@poliwangi.ac.id Web Site: http://www.poliwangi.ac.id
            </p>
        </div>
    </div>

    <div class="title">
        <u>FORMULIR PENDAFTARAN ANGGOTA</u><br>
        @if($user->nip)
            KOPERASI KARYAWAN POLITEKNIK NEGERI BANYUWANGI
        @else
            KOPERASI POLITEKNIK NEGERI BANYUWANGI
        @endif
    </div>


    <p>Yang bertandatangan di bawah ini:</p>
    <table>
        <tr>
            <td style="width: 30%;">Nama</td>
            <td>: <strong>{{ $user->nama }}</strong></td>
        </tr>
        <tr>
            @if($user->nip)
                <td>NIP/NIK/NIPPPK</td>
                <td>:
                    <strong>{{ $user->nip }}</strong>

                </td>
            @else
                -
            @endif
        </tr>

        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>: <strong>{{ $user->tempat_lahir }},
                    {{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') }}</strong></td>
        </tr>
        <tr>
            <td>Alamat Rumah</td>
            <td>: <strong>{{ $user->alamat_rumah }}</strong></td>
        </tr>
        <tr>
            <td>Nomor HP/Whatsapp</td>
            <td>: <strong>{{ $user->no_telepon }}</strong></td>
        </tr>
        <tr>
            <td>Unit Kerja</td>
            <td>: <strong>{{ $user->unit_kerja }}</strong></td>
        </tr>
    </table>

    <p style="text-align: justify; margin-top: 20px;">
        Dengan ini mengajukan permohonan masuk menjadi anggota Koperasi Karyawan Politeknik Negeri Banyuwangi dan
        bersedia melaksanakan ketentuan yang ada, sebagai berikut:
    </p>

    <ol class="content-list">
        <li>Mentaati Anggaran Dasar dan Anggaran Rumah Tangga (AD/ART) dan peraturan yang berlaku di Koperasi Karyawan
            Politeknik Negeri Banyuwangi.</li>
        <li>Melampirkan fotokopi SK Perjanjian Kerja.</li>
        <li>Membayar Simpanan Pokok sebesar Rp. 50.000,- yang dibayarkan sekali pada saat mendaftar.</li>
        <li>Membayar Simpanan Wajib setiap awal bulan sejak diterima menjadi anggota sebesar
            @if($simpanan_wajib)
                <strong>Rp. {{ number_format($simpanan_wajib->nilai, 0, ',', '.') }},-</strong>
            @else
                sesuai ketentuan pengurus.
            @endif
        </li>
        <li>Membayar Simpanan Sukarela (Tabungan) setiap bulan sesuai keinginan masing-masing anggota (tidak wajib).
        </li>
    </ol>

    <p style="padding-left: 20px;">
        Simpanan Sukarela akan dibagikan bersama dengan bingkisan hari raya.
    </p>
    <p>Pembayaran simpanan dilakukan melalui bendahara gaji yang dipotong setiap awal bulan.</p>

    <p style="margin-top: 20px;">Demikian permohonan ini dibuat untuk menjadi bahan pertimbangan bagi Pengurus. Atas
        perhatian Bapak/Ibu, disampaikan terima kasih.</p>

    <table class="signature">
        <tr>
            <td>
                Mengetahui,<br>
                Bendahara Gaji,
                @if($user->nip)
                    <br>Politeknik Negeri Banyuwangi
                    <br><br><br><br><br>
                    <strong><u>Nova Victor Geral Dino, S.E.</u></strong>
                @else
                    <br><br><br><br><br>
                    <strong><u>--------------------</u></strong>
                @endif
            </td>

            <td>
                Banyuwangi, {{ \Carbon\Carbon::parse($tanggal_permohonan)->format('d F Y') }}<br>
                Pemohon,
                <br><br><br><br><br>
                <strong><u>{{ $user->nama }}</u></strong>
            </td>
        </tr>
    </table>
    </div>

</body>

</html>