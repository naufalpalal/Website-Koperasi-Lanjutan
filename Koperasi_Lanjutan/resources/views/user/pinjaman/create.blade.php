@extends('user.index')

@section('title', 'Formulir Pengajuan & Upload Dokumen Pinjaman')

@section('content')
    <div class="max-w-3xl mx-auto bg-white shadow rounded-xl p-6 mt-8">

        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">
            🏛️ Formulir Pengajuan & Upload Dokumen Pinjaman
        </h2>

        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md">
                {{ session('success') }}
            </div>
        @endif


        {{-- ======================================================
        ANGGOTA BELUM MEMILIKI PINJAMAN
    ======================================================= --}}
        @if (!$pinjaman)

            {{-- Peringatan jika belum 6 bulan --}}
            @if (!$bolehPinjam)
                <div class="bg-red-100 text-red-700 border border-red-300 p-3 rounded mb-4">
                    <strong>Anda belum dapat mengajukan pinjaman.</strong><br>
                    Minimal masa keanggotaan: <strong>6 bulan</strong>.<br>
                    Sisa waktu: <strong>{{ $sisaBulan }} bulan lagi</strong>.
                </div>
            @endif


            <form action="{{ route('user.pinjaman.store') }}" method="POST">
                @csrf

                {{-- Nominal --}}
                <label class="block font-semibold mb-1">Nominal Pinjaman</label>
                <input type="number" name="nominal" class="w-full border p-2 rounded" required
                    {{ !$bolehPinjam ? 'disabled' : '' }}>

                {{-- Tenor --}}
                <label class="block font-semibold mt-4 mb-1">Pilih Tenor</label>
                <select name="tenor" id="tenorSelect" class="w-full border p-2 rounded" required
                    {{ !$bolehPinjam ? 'disabled' : '' }}>
                    <option value="">-- Pilih Tenor --</option>
                    @foreach ($settings as $set)
                        <option value="{{ $set->tenor }}" data-bunga="{{ $set->bunga }}">
                            {{ $set->tenor }} bulan
                        </option>
                    @endforeach
                </select>

                {{-- Bunga otomatis --}}
                <label class="block font-semibold mt-4 mb-1">Bunga</label>
                <input type="text" id="bungaValue" class="w-full border p-2 rounded bg-gray-100" readonly>
                <input type="hidden" name="bunga" id="bungaInput">

                {{-- Tombol --}}
                <button
                    class="w-full mt-5 bg-green-600 text-white py-2 rounded hover:bg-green-700 
        {{ !$bolehPinjam ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ !$bolehPinjam ? 'disabled' : '' }}>
                    Ajukan Pinjaman
                </button>

            </form>

            <script>
                document.getElementById('tenorSelect').addEventListener('change', function() {
                    const bunga = this.options[this.selectedIndex].dataset.bunga;
                    document.getElementById('bungaValue').value = bunga + '%';
                    document.getElementById('bungaInput').value = bunga;
                });
            </script>

            {{-- ======================================================
        STATUS PENDING
    ======================================================= --}}
        @elseif ($pinjaman->status === 'pending')
            <p><strong>Nominal Pinjaman:</strong>
                Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}</p>

            <p class="text-yellow-600 font-semibold mb-4">
                ⏳ Menunggu persetujuan pengurus...
            </p>

            <hr class="my-4">

            <h3 class="text-lg font-semibold text-gray-700 mb-3">📄 Dokumen Pengajuan</h3>

            <div class="space-y-3">
                <a href="{{ route('anggota.pinjaman.download', 1) }}" class="btn btn-primary">
                    Download Dokumen Pernyataan
                </a>

                <a href="{{ route('anggota.pinjaman.download', 2) }}" class="btn btn-secondary">
                    Download Surat Permohonan
                </a>
            </div>

            <p class="text-gray-600 text-sm mt-2">
                Tanda tangani dokumen lalu upload kembali.
            </p>

            <form action="{{ route('user.pinjaman.upload', $pinjaman->id) }}" method="POST" enctype="multipart/form-data"
                class="mt-6">
                @csrf

                <label class="block text-gray-600 mb-2">Upload 2 Dokumen (PDF)</label>
                <input type="file" name="dokumen_pinjaman[]" accept="application/pdf" multiple
                    class="border rounded w-full p-2 mb-4">

                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    📤 Upload Dokumen
                </button>
            </form>


            {{-- ======================================================
        STATUS DISETUJUI → TAMPILKAN JADWAL ANGSURAN
    ======================================================= --}}
        @elseif ($pinjaman->status === 'disetujui')
            <h2 class="text-xl font-semibold mb-4">📑 Jadwal Angsuran</h2>

            <div class="bg-white shadow rounded p-4 border mb-6">
                <p><strong>Total Pinjaman:</strong> Rp{{ number_format($pinjaman->nominal, 0, ',', '.') }}</p>
                <p><strong>Tenor:</strong> {{ $pinjaman->tenor }} bulan</p>
            </div>

            @if ($angsuran->count() > 0)

                <form action="{{ route('anggota.angsuran.pilih', $pinjaman->id) }}" method="GET">
                    @csrf

                    <table class="w-full border mb-4">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="p-2 border text-center">Pilih</th>
                                <th class="p-2 border">Bulan Ke</th>
                                <th class="p-2 border">Jatuh Tempo</th>
                                <th class="p-2 border">Angsuran</th>
                                <th class="p-2 border">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($angsuran as $row)
                                <tr>
                                    <td class="p-2 border text-center">
                                        @if ($row->status === 'belum_lunas')
                                            <input type="checkbox" name="angsuran_ids[]" value="{{ $row->id }}">
                                        @endif
                                    </td>

                                    <td class="p-2 border text-center">{{ $row->bulan_ke }}</td>

                                    <td class="p-2 border text-center">{{ $row->tanggal_bayar }}</td>

                                    <td class="p-2 border text-right">
                                        Rp{{ number_format($row->jumlah_bayar, 0, ',', '.') }}
                                    </td>

                                    <td class="p-2 border text-center">
                                        @if ($row->status === 'lunas')
                                            <span class="text-green-600 font-bold">Lunas</span>
                                        @else
                                            <span class="text-red-600 font-bold">Belum Lunas</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        💰 Bayar Angsuran
                    </button>
                </form>
            @else
                <p class="text-gray-500 italic">Belum ada angsuran.</p>
            @endif

        @endif

    </div>
@endsection
