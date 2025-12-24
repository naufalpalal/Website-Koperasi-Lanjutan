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
        {{-- LOGIKA STATUS --}}
        @if ($pinjaman->status === 'draft')
            @include('user.pinjaman.partials._upload_form')

        @elseif ($pinjaman->status === 'pending')
            @include('user.pinjaman.partials._status_pending')

        @elseif ($pinjaman->status === 'disetujui')
            @include('user.pinjaman.partials._status_approved')

        @elseif ($pinjaman->status === 'ditolak')
            @include('user.pinjaman.partials._status_rejected')
        @endif

    </div>
@endsection