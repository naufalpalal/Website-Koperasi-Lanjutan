@extends('Pengurus.index')

@section('content')

    <div class="w-full max-w-5xl mx-auto p-2 sm:p-4">

        {{-- CARD UTAMA --}}
        <div class="bg-white rounded-xl shadow-lg p-6">

            {{-- HEADER --}}
            <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
                    Detail Pengajuan Pinjaman
                </h2>

                <span class="px-3 py-1 text-sm font-semibold rounded-full
                        @if ($pinjaman->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($pinjaman->status == 'disetujui') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst($pinjaman->status) }}
                </span>
            </div>

            {{-- INFO PEMINJAM --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-sm text-gray-500">Nama Pemohon</p>
                    <p class="text-lg font-semibold">{{ $pinjaman->user->nama }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Nominal Pinjaman</p>
                    <p class="text-lg font-semibold text-green-700">
                        Rp{{ number_format($pinjaman->nominal, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            <hr class="my-6">
            <div class="relative border rounded-xl overflow-hidden bg-gray-100">
                 {{-- PDF / GAMBAR --}}
                @php
                    $dokumen = $pinjaman->dokumen_verifikasi;
                    $url = asset('storage/' . $dokumen);
                    $ext = strtolower(pathinfo($dokumen, PATHINFO_EXTENSION));
                @endphp

                @if ($ext === 'pdf')
                    <iframe
                        src="{{ $url }}#toolbar=0&navpanes=0&scrollbar=0"
                        class="w-full h-[600px] bg-white">
                    </iframe>
                @else
                    <img
                        src="{{ $url }}"
                        class="w-full max-h-[600px] object-contain bg-white">
                @endif

                {{-- TOMBOL DOWNLOAD --}}
                <div class="absolute bottom-3 right-3">
                    <a href="{{ $url }}" download
                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white
                            text-sm font-semibold rounded-lg shadow
                            hover:bg-blue-700 transition">
                        ⬇ Download Dokumen
                    </a>
                </div>
            </div>

            <hr class="my-6">
            {{-- AKSI --}}
            <div class="flex flex-wrap justify-end gap-3">
                <form action="{{ route('pengurus.pinjaman.reject', $pinjaman->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition">
                        ❌ Tolak
                    </button>
                </form>

                <form action="{{ route('pengurus.pinjaman.approve', $pinjaman->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-5 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition">
                        ✅ Setujui
                    </button>
                </form>
            </div>

        </div>
    </div>

@endsection