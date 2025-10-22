@extends('user.index')

@section('title', 'Upload Surat Pinjaman')

@section('content')
    <div class="max-w-3xl mx-auto bg-white shadow rounded-xl p-6 mt-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Surat Pengajuan Pinjaman</h2>

        <div class="mb-6">
            <p><strong>Nominal Pinjaman:</strong> Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($pinjaman->status) }}</p>
        </div>

        {{-- Tombol Download --}}
        <a href="{{ route('user.pinjaman.download', $pinjaman->id) }}"
            class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            📥 Download Surat Pinjaman
        </a>

        {{-- Form Upload --}}
        <form action="{{ route('user.pinjaman.upload', $pinjaman->dokumen_pinjaman) }}" method="POST" enctype="multipart/form-data"
            class="mt-6">
            @csrf
            <label for="dokumen_pinjaman" class="block text-gray-600 mb-2">
                Silakan upload kembali surat yang diperlukan untuk melakukan pinjaman
            </label>
            <input type="file" name="dokumen_pinjaman[]" id="dokumen_pinjaman" accept="application/pdf" multiple
                class="border border-gray-300 rounded-lg w-full p-2 mb-4">
            {{-- Preview PDF --}}
            <div id="previewContainer" class="mt-4 space-y-2">
                {{-- PDF preview akan muncul di sini --}}
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                📤 Upload ke Pengurus
            </button>
        </form>

        {{-- Bagian dokumen (kosong) --}}
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">📄 Dokumen yang Telah Diupload</h3>
            <div class="space-y-2">
                <p class="text-gray-600">Tidak ada dokumen yang diupload.</p>
            </div>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
            <div class="mt-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endsection
