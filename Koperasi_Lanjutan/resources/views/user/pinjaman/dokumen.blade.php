@extends('user.index')

@section('title', 'Persyaratan Pengajuan Pinjaman')

@section('content')
<div class="p-6 bg-white rounded-xl shadow-lg max-w-2xl mx-auto">

    {{-- Judul --}}
    <h2 class="text-2xl font-bold mb-4">Persyaratan Pengajuan Pinjaman</h2>

    {{-- Informasi Paket --}}
    <div class="mb-5 p-4 bg-gray-100 rounded-lg">
        <p><strong>Jenis Paket:</strong> {{ $pinjaman->paket->nama_paket }}</p>
        <p><strong>Nominal:</strong> Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}</p>
        <p><strong>Tenor:</strong> {{ $pinjaman->tenor }} bulan</p>
        <p><strong>Bunga:</strong> {{ rtrim(rtrim(number_format($pinjaman->bunga, 2), '0'), '.') }}%</p>
        <p><strong>Status:</strong>
            <span class="text-yellow-600 font-semibold">Pending</span>
        </p>
    </div>

    <p class="text-gray-700 mb-3">
        Silakan download dan tanda tangani kedua dokumen berikut:
    </p>

    {{-- Download Dokumen --}}
    <div class="space-y-3 mb-6">
        <a href="{{ route('anggota.pinjaman.download', 1) }}" class="text-blue-600 hover:underline flex items-center gap-1">
            ⬇️ Download Dokumen Pernyataan
        </a>

        <a href="{{ route('anggota.pinjaman.download', 2) }}" class="text-blue-600 hover:underline flex items-center gap-1">
            ⬇️ Download Surat Permohonan
        </a>
    </div>

    <p class="text-gray-600 text-sm mb-6">
        Setelah ditandatangani, upload kembali kedua dokumen tersebut dalam format PDF.
    </p>

    {{-- Upload Dokumen --}}
    <form action="{{ route('user.pinjaman.upload', $pinjaman->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label class="block text-gray-700 font-semibold mb-2">Upload 2 Dokumen (PDF)</label>
        <input type="file"
               name="dokumen_pinjaman[]"
               accept="application/pdf"
               multiple
               class="border rounded w-full p-2 mb-4">

        <button class="bg-green-600 text-white w-full py-2 rounded-lg hover:bg-green-700 transition">
            ⬆️ Upload Dokumen
        </button>
    </form>

</div>
@endsection
