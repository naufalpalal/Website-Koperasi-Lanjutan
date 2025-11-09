<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Surat Permohonan Pinjaman</title>
    <style>
        /* Print-friendly, close to letter appearance in image */
        body {
            font-family: "Times New Roman", Times, serif;
            color: #000;
            padding: 36px;
        }

        .container {
            max-width: 720px;
            margin: 0 auto;
        }

        .right {
            text-align: right;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        .mb-2 {
            margin-bottom: .5rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .signature-area {
            height: 80px;
        }

        .small {
            font-size: 0.9rem;
        }

        .underline {
            text-decoration: underline;
        }

        .center {
            text-align: center;
        }

        .two-cols {
            display: flex;
            justify-content: space-between;
            margin-top: 48px;
        }

        .col {
            width: 48%;
            text-align: center;
        }

        /* Updated styles for signatures */
        .signature-section {
            margin-top: 20px;
            width: 100%;
        }

        .signature-right {
            float: right;
            text-align: left;
            margin-bottom: 50px;
        }

        .signature-bottom {
            clear: both;
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .signature-box {
            text-align: left;
        }

        .signature-name {
            margin-top: 50px;
            font-weight: bold;
        }

        .signature-title {
            margin-bottom: 10px;
        }

        table.info {
            width: 100%;
            margin-top: 8px;
        }

        table.info td {
            vertical-align: top;
            padding: 2px 6px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Tanggal -->
        <div class="right small">
            @php $dt = isset($tanggal) ? \Carbon\Carbon::parse($tanggal) : now(); @endphp
            Banyuwangi, {{ $dt->format('d') }} {{ $dt->translatedFormat('F Y') }}
        </div>

        <div class="mt-3">
            <strong>Kepada Yth.</strong><br>
            Bendahara Politeknik Negeri Banyuwangi<br>
            di -<br>
            &nbsp;&nbsp;&nbsp;&nbsp;Tempat
        </div>

        <div class="mt-3">
            Yang bertanda tangan dibawah ini :
            <table class="info">
                <tr>
                    <td style="width:120px">Nama</td>
                    <td>: <strong>{{ $pemohon->nama ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>NIP/KPPK</td>
                    <td>: {{ $pemohon->nip_kppk ?? $pemohon->nip ?? '-' }}</td>
                </tr>
                <tr>
                    <td>No. KTP</td>
                    <td>: {{ $pemohon->no_ktp ?? $pemohon->ktp ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>: {{ $pemohon->jabatan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>: {{ $pemohon->alamat_rumah ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="mt-3">
            Dengan ini bermaksud mengajukan pinjaman pada Koperasi Karyawan Politeknik Negeri Banyuwangi sebesar
            <strong>Rp {{ number_format($jumlah ?? 10000000, 0, ',', '.') }},-
                ({{ $jumlah_terbilang ?? 'Sepuluh Juta Rupiah' }})</strong>,
            dan pinjaman tersebut diangsur <strong>{{ $lama_angsuran ?? 10 }} bulan</strong> dengan jumlah angsuran
            sebesar
            <strong>Rp {{ number_format($angsuran ?? 1100000, 0, ',', '.') }},-
                ({{ $angsuran_terbilang ?? 'Satu Juta Seratus Rupiah' }})</strong>.
        </div>

        <div class="mt-3 mb-4">
            Demikian pengajuan ini, atas perkenan nya disampaikan terima kasih.
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-right">
                Hormat kami,<br>
                Pemohon<br>
                <div class="signature-name">
                    {{ $pemohon->nama ?? 'EVA OLIVIA PUTASOIT, S.T.,M.T.' }}
                </div>
            </div>

            <div class="signature-bottom">
                <div class="signature-box">
                    <div class="signature-title">Mengetahui,<br>Wadir II Bidang Umum & Keuangan</div>
                    <div class="signature-name">
                            ({{ $identitas->nama_wadir ?? 'Devit Suwardiyanto,S.Si.,M.T.' }})</div>
                </div>

                <div class="signature-box">
                    <div class="signature-title">Menyetujui,<br>Bendahara Pengeluaran</div>
                    <div class="signature-name">
                            ({{ $identitas->nama_bendahara_pengeluaran ?? 'Imarotul Husna, S.E.' }})</div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>