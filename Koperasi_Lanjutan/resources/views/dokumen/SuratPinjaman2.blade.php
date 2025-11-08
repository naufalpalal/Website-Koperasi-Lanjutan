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

        .stamp-placeholder {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            border: 2px solid #0b5;
            display: inline-block;
            margin-top: 8px;
        }

        .sign-img {
            max-height: 70px;
            display: block;
            margin: 0 auto;
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

        <div class="mt-6" style="display:flex; justify-content:flex-end; width:100%;">
            <div style="width:240px; text-align:center;">
                <div>Hormat kami,</div>
                <div>Pemohon</div>

                <div class="signature-area">
                    @if(isset($pemohon->signature_path) && $pemohon->signature_path)
                        <img src="{{ asset('storage/' . $pemohon->signature_path) }}" alt="Tanda Tangan" class="sign-img">
                    @else
                        <!-- blank space for signature -->
                        <div style="height:64px;"></div>
                    @endif
                </div>

                <div class="underline"><strong>{{ $pemohon->nama ?? '-' }}</strong></div>
                <div class="small">{{ $pemohon->nip_kppk ?? $pemohon->nip ?? '' }}</div>
            </div>
        </div>

        <!-- Pejabat Mengetahui & Menyetujui -->
        <div class="two-cols">
            <div class="col">
                <div class="small">Mengetahui,<br>{{ $mengetahui->title ?? 'Wadir III Bidang Umum & Keuangan' }}</div>
                <div class="stamp-placeholder">
                    @if(isset($mengetahui->signature_path) && $mengetahui->signature_path)
                        <img src="{{ asset('storage/' . $mengetahui->signature_path) }}" alt="Tanda Tangan"
                            style="max-width:100%; max-height:110px; display:block; margin:8px auto 0;">
                    @endif
                </div>
                <div class="mt-2 underline"><strong>{{ $mengetahui->nama ?? 'DEVIT SUPARDIANTO, S.SI., M.T.' }}</strong>
                </div>
                <div class="small">NIP. {{ $mengetahui->nip ?? '198311052015041001' }}</div>
            </div>

            <div class="col">
                <div class="small">Menyetujui,<br>{{ $menyetujui->title ?? 'Bendahara Pengeluaran' }}</div>
                <div class="stamp-placeholder">
                    @if(isset($menyetujui->signature_path) && $menyetujui->signature_path)
                        <img src="{{ asset('storage/' . $menyetujui->signature_path) }}" alt="Tanda Tangan"
                            style="max-width:100%; max-height:110px; display:block; margin:8px auto 0;">
                    @endif
                </div>
                <div class="mt-2 underline"><strong>{{ $menyetujui->nama ?? 'IMAROTUL HUSNA, S.E.' }}</strong></div>
                <div class="small">NIP. {{ $menyetujui->nip ?? '198605182014042001' }}</div>
            </div>
        </div>
    </div>
</body>

</html>