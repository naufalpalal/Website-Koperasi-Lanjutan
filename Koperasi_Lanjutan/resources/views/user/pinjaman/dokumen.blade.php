@extends('user.index')

@section('title', 'Persyaratan Pengajuan Pinjaman')

@section('content')
    <div class="p-6 bg-white rounded-xl shadow-lg max-w-2xl mx-auto">

        {{-- Alert --}}
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Judul --}}
        <h2 class="text-2xl font-bold mb-4">Persyaratan Pengajuan Pinjaman</h2>

        {{-- Informasi Paket --}}
        <div class="mb-5 p-4 bg-gray-100 rounded-lg">
            <p><strong>Jenis Paket:</strong> {{ $pinjaman->paket->nama_paket }}</p>
            <p><strong>Nominal:</strong> Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}</p>
            <p><strong>Tenor:</strong> {{ $pinjaman->tenor }} bulan</p>
            <p><strong>Bunga:</strong> {{ rtrim(rtrim(number_format($pinjaman->bunga, 2), '0'), '.') }}%</p>
        </div>

        {{-- Download --}}
        <p class="text-gray-700 mb-3">
            Silakan download dan tanda tangani dokumen berikut:
        </p>

        <div class="mb-6">
            <a href="{{ route('anggota.pinjaman.download', 2) }}"
                class="text-blue-600 hover:underline flex items-center gap-1">
                ⬇️ Download Surat Permohonan
            </a>
        </div>

        <p class="text-gray-600 text-sm mb-6">
            Setelah ditandatangani, upload kembali dokumen dalam format PDF.
        </p>

        {{-- Upload --}}
        @if($pinjaman->status !== 'pending')
            <form action="{{ route('user.pinjaman.upload', $pinjaman->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label class="block text-gray-700 font-semibold mb-2">
                    Upload Dokumen Verifikasi (PDF)
                </label>

                <input type="file" name="dokumen_verifikasi" accept="application/pdf" required
                    class="border rounded w-full p-2 mb-4">


                <button class="bg-green-600 text-white w-full py-2 rounded-lg hover:bg-green-700 transition">
                    ⬆️ Upload Dokumen
                </button>
            </form>
        @else
            <div class="p-4 bg-yellow-100 text-yellow-800 rounded">
                Dokumen sudah diupload. Menunggu verifikasi pengurus.
            </div>
        @endif

    </div>
@endsection