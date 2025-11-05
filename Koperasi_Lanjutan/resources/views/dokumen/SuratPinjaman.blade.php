<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pernyataan Kredit - Koperasi Karyawan Poliwangi</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .container {
                width: 190mm;
                min-height: 297mm;
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            background: #fff;
        }

        .container {
            width: 190mm;
            min-height: 297mm;
            margin: 0 auto;
            background: white;
            box-sizing: border-box;
            box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid black;
            padding-bottom: 8px;
            margin-bottom: 12px;
            position: relative;
        }

        .header h1 {
            font-size: 13pt;
            margin: 2px 0;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .header p {
            font-size: 9pt;
            margin: 1px 0;
            line-height: 1.3;
        }

        .title-bar {
            background-color: #A9A9A9;
            text-align: center;
            font-weight: bold;
            padding: 5px;
            margin: 12px 0 8px 0;
            font-size: 10.5pt;
            border: 1px solid #707070;
            letter-spacing: 0.3px;
        }

        .form-table {
            width: 100%;
            margin-bottom: 8px;
            font-size: 10.5pt;
        }

        .form-table td {
            padding: 2px 0;
            vertical-align: top;
            word-break: break-word;
        }

        .form-table .label {
            width: 130px;
        }

        .form-table .colon {
            width: 8px;
        }

        .form-table .value {
            font-weight: normal;
        }

        .content {
            text-align: justify;
            font-size: 10.5pt;
            margin: 8px 0;
        }

        ol {
            padding-left: 18px;
            text-align: justify;
            margin: 8px 0;
            font-size: 10.5pt;
        }

        ol li {
            margin-bottom: 6px;
            line-height: 1.4;
        }

        .closing {
            text-align: justify;
            margin: 10px 0 5px 0;
            font-size: 10.5pt;
        }

        .date-location {
            text-align: left;
            margin-top: 15px;
            margin-bottom: 5px;
            font-size: 10.5pt;
        }

        .signature-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            text-align: center;
            font-size: 10pt;
        }

        .signature-table td {
            width: 33.33%;
            padding: 3px;
            vertical-align: top;
        }

        .signature-title {
            margin-bottom: 55px;
            line-height: 1.3;
        }

        .signature-name {
            font-weight: bold;
            line-height: 1.3;
        }

        .signature-note {
            font-size: 9.5pt;
            margin-top: 2px;
        }

        .footer-note {
            position: relative;
            margin-top: 25px;
            page-break-inside: avoid;
            font-size: 9pt;
            font-style: italic;
        }
    </style>
</head>

<body>

    @php
        $user = auth()->user();
        $identitas = \App\Models\IdentitasKoperasi::first();
    @endphp

    <div class="container">
        <div class="header">
            <h1>KOPERASI KARYAWAN</h1>
            <h1>POLITEKNIK NEGERI BANYUWANGI</h1>
            <p>Jalan Raya Jember Km. 13 Labanasem, Kabat, Banyuwangi (68461)</p>
            <p>Telepon / Fax : (0333) 636780</p>
            <p>E-mail : poliwangi@poliwangi.ac.id Web Site: http://www.poliwangi.ac.id</p>
        </div>

        <div class="title-bar">DATA PEMOHON KREDIT</div>

        <table class="form-table">
            <tr>
                <td class="label">Nama Pemohon</td>
                <td class="colon">:</td>
                <td class="value">{{ $user->nama }}</td>
            </tr>
            <tr>
                <td class="label">NIP/NIK/NIPPPK</td>
                <td class="colon">:</td>
                <td class="value">{{ $user->nip }}</td>
            </tr>
            <tr>
                <td class="label">Unit Kerja</td>
                <td class="colon">:</td>
                <td class="value">{{ $user->unit_kerja }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Rumah</td>
                <td class="colon">:</td>
                <td class="value">{{ $user->alamat }}</td>
            </tr>
            <tr>
                <td class="label">Jumlah Pinjaman</td>
                <td class="colon">:</td>
                <td class="value">{{ $pinjaman->nominal }}</td>
            </tr>
            <tr>
                <td class="label">Telepon</td>
                <td class="colon">:</td>
                <td class="value">{{ $user->telepon }}</td>
            </tr>
            <tr>
                <td class="label">Nama Istri/Suami</td>
                <td class="colon">:</td>
                <td class="value">{{ $user->nama_pasangan }}</td>
            </tr>
            <tr>
                <td class="label">Status SK Kerja</td>
                <td class="colon">:</td>
                <td class="value">: Tetap (PNS) / Kontrak berakhir pada {{ $user->status_sk }}</td>
            </tr>
        </table>

        <div class="title-bar">SURAT PERNYATAAN DAN KUASA</div>

        <p class="content">Yang bertanda tangan dibawah ini Saya:</p>

        <table class="form-table">
            <tr>
                <td class="label">Nama Pemohon</td>
                <td class="colon">:</td>
                <td class="value">{{ $user->nama }}</td>
            </tr>
            <tr>
                <td class="label">NIP/NIK/NIPPPK</td>
                <td class="colon">:</td>
                <td class="value">{{ $user->nip }}</td>
            </tr>
            <tr>
                <td class="label">Unit Kerja</td>
                <td class="colon">:</td>
                <td class="value">{{ $user->unit_kerja }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Rumah</td>
                <td class="colon">:</td>
                <td class="value">{{ $user->alamat }}</td>
            </tr>
            <tr>
                <td class="label">Telepon</td>
                <td class="colon">:</td>
                <td class="value">{{ $user->telepon }}</td>
            </tr>
        </table>

        <p class="content">Menyatakan menerima ketentuan pinjaman sebagaimana tertulis ini, selanjutnya pinjaman
            tersebut menjadi pinjaman pribadi Saya.</p>

        <ol>
            <li>Pinjaman / Kredit tersebut akan diangsur dalam jangka waktu 10 bulan (wajib di isi oleh pemohon)</li>
            <li>Atas pinjaman / kredit tersebut Saya bersedia dikenakan bunga (tetap) 1 (satu) %/bulan.</li>
            <li>Untuk pelunasan fasilitas kredit tersebut, Saya memberikan kuasa penuh yang tidak dapat dibatalkan /
                dicabut kepada Bendahara KKP guna mendebet gaji / tunjangan lainnya yang selanjutnya dibayarkan sebagai
                angsuran bulanan / pelunasan kredit yang saya peroleh dari KKP. Apabila Saya keluar dari keanggotaan
                Koperasi, maka KKP berhak untuk melakukan pendebetan sebesar jumlah tunggakan yang menjadi kewajiban
                Saya, langsung dari gaji / benefit terakhir yang menjadi hak Saya sebagai anggota.</li>
            <li>Apabila seluruh kompensasi tunjangan masih belum mencukupi pelunasan kredit, Saya bersedia melunasi sisa
                kredit pinjaman di atas baik secara tunai atau dengan harta milik pribadi maupun keluarga.</li>
        </ol>

        <p class="closing">Apabila Saya melalaikan hal-hal yang telah Saya sebutkan diatas, maka Saya bersedia dituntut
            sesuai dengan hukum yang berlaku.</p>

        <p class="closing">Demikian surat pernyataan dan kuasa ini Saya buat dengan sebenar-benarnya dan digunakan
            sebagaimana mestinya.</p>

        <div class="date-location">
            Banyuwangi, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>

        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td>
                        <div class="signature-title">Yang memberi pernyataan & kuasa</div>
                        <div class="signature-name">(...{{ $user->nama }})<br><span
                                class="signature-note">Pemohon</span></div>
                    </td>
                    <td>
                        <div class="signature-title">Mengetahui<br>Bendahara Koperasi</div>
                        <div class="signature-name">
                            ({{ $identitas->nama_bendahara_koperasi ?? 'Erlina Kusumawati, S.ST.' }})</div>
                    </td>
                    <td>
                        <div class="signature-title">Disetujui oleh<br>Ketua Koperasi</div>
                        <div class="signature-name">
                            ({{ $identitas->nama_ketua_koperasi ?? 'Dr. Danang S.W.P.J. Widakdo, S.P., M.M' }})</div>
                    </td>
                </tr>
            </table>
        </div>

        <p class="footer-note">Nb: Form Paling Lambat disetor 1 minggu dari tanggal pengajuan</p>
    </div>

</body>

</html>