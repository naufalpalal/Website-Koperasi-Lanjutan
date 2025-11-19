<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Surat Permohonan Pinjaman</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            color: #000;
            padding: 36px;
            background: #f5f5f5;
        }

        .container {
            max-width: 720px;
            margin: 0 auto;
            background: white;
            padding: 40px 60px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

        .small {
            font-size: 0.9rem;
        }

        table.info {
            width: 100%;
            margin-top: 8px;
            border-collapse: collapse;
        }

        table.info td {
            vertical-align: top;
            padding: 2px 6px;
        }

        /* SECTION TANDA TANGAN SEJAJAR */
        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 60px;
            text-align: center;
        }

        .signature-box {
            width: 30%;
        }

        .signature-title {
            margin-bottom: 60px;
            /* ruang untuk tanda tangan */
        }

        .signature-name {
            font-weight: bold;
        }

        .signature-nip {
            font-size: 0.85em;
            font-weight: normal;
        }

        /* Agar tampilan tetap sejajar walau tinggi teks berbeda */
        .signature-section>div {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        /* Responsive fallback */
        @media print {
            body {
                background: none;
                padding: 0;
            }

            .container {
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
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
            Yang bertanda tangan di bawah ini :
            <table class="info">
                <tr>
                    <td style="width:120px">Nama</td>
                    <td>: <strong>{{ $pemohon->nama ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>NIP/KPPK</td>
                    <td>: {{ $pemohon->nip_kppk ?? ($pemohon->nip ?? '-') }}</td>
                </tr>
                <tr>
                    <td>No. KTP</td>
                    <td>: {{ $pemohon->no_ktp ?? ($pemohon->ktp ?? '-') }}</td>
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
            Demikian pengajuan ini, atas perkenannya disampaikan terima kasih.
        </div>

        <!-- TANDA TANGAN SEJAJAR -->
        <!-- Bagian tanda tangan -->
        <!-- SPASI BESAR ANTARA ISI SURAT DAN TTD -->
        <div style="height: 80px;"></div>

        <!-- BARIS 1: PEMOHON DI KANAN -->
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="width:33%;"></td>
                <td style="width:33%;"></td>
                <td style="width:33%; text-align:center;">
                    Banyuwangi, {{ \Carbon\Carbon::parse($tanggal_permohonan)->format('d F Y') }}<br>
                    Pemohon
                    <div style="height:90px;"></div>
                    <strong><u>{{ $user->nama }}</u></strong>
                </td>
            </tr>
        </table>

        <!-- SPASI ANTARA PEMOHON & WADIR/BENDAHARA -->
        <div style="height: 40px;"></div>

        <!-- BARIS 2: WADIR & BENDAHARA -->
        <!-- BARIS 2: WADIR & BENDAHARA -->
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                {{-- WADIR --}}
                @if($user->nip)
                    <td style="width:50%; text-align:center; vertical-align: top;">
                        Mengetahui,<br>
                        Wadir II Bidang Umum & Keuangan
                        <div style="height:90px;"></div>
                        <strong><u>{{ $identitas->nama_wadir }}</u></strong>
                    </td>
                @endif

                {{-- BENDAHARA – POSISI DI KANAN --}}
                <td style="width:50%; text-align:center; vertical-align: top;">
                    Menyetujui,<br>
                    Bendahara Gaji
                    @if($user->nip)
                        <br>Politeknik Negeri Banyuwangi
                    @endif
                    <div style="height:90px;"></div>
                    <strong><u>{{ $identitas->bendahara_gaji }}</u></strong>
                </td>
            </tr>
        </table>


    </div>
</body>

</html>