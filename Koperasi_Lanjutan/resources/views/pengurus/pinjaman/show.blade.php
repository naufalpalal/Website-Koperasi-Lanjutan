@extends('Pengurus.index')

@section('content')
    <div class="p-6 bg-white rounded shadow">
        <h2 class="text-xl font-bold mb-4">Detail Pengajuan Pinjaman</h2>

        <p><strong>Nama:</strong> {{ $pinjaman->user->nama }}</p>
        <p><strong>Nominal:</strong> Rp{{ number_format($pinjaman->nominal, 0, ',', '.') }}</p>

        <p><strong>Status:</strong>
            <span
                class="px-2 py-1 rounded
                @if ($pinjaman->status == 'pending') bg-yellow-200 text-yellow-800
                @elseif($pinjaman->status == 'disetujui') bg-green-200 text-green-800
                @else bg-red-200 text-red-800 @endif">
                {{ ucfirst($pinjaman->status) }}
            </span>
        </p>

        <h3 class="font-bold text-lg mt-4">Dokumen Pinjaman</h3>
        @if ($pinjaman->dokumen_pinjaman)
            @php
                $dokpin = $pinjaman->dokumen_pinjaman;
                $urlPin = asset('storage/' . $dokpin);
                $extPin = strtolower(pathinfo($dokpin, PATHINFO_EXTENSION));
            @endphp

            @if ($extPin === 'pdf')
                <iframe src="{{ $urlPin }}" width="100%" height="500" class="border rounded-lg mt-2"></iframe>
            @else
                <img src="{{ $urlPin }}" class="mt-2 w-full max-w-2xl rounded shadow">
            @endif
        @else
            <p class="text-red-500 italic">Tidak ada dokumen pinjaman.</p>
        @endif


        <h3 class="font-bold text-lg mt-6">Dokumen Verifikasi</h3>
        @if ($pinjaman->dokumen_verifikasi)
            @php
                $dokver = $pinjaman->dokumen_verifikasi;
                $urlVer = asset('storage/' . $dokver);
                $extVer = strtolower(pathinfo($dokver, PATHINFO_EXTENSION));
            @endphp

            @if ($extVer === 'pdf')
                <iframe src="{{ $urlVer }}" width="100%" height="500" class="border rounded-lg mt-2"></iframe>
            @else
                <img src="{{ $urlVer }}" class="mt-2 w-full max-w-2xl rounded shadow">
            @endif
        @else
            <p class="text-red-500 italic">Tidak ada dokumen verifikasi.</p>
        @endif



        {{-- Form Persetujuan --}}
        <form action="{{ route('pengurus.pinjaman.approve', $pinjaman->id) }}" method="POST" class="mt-6">
            @csrf
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded mt-4 hover:bg-green-700">
                ✅ Setujui
            </button>
        </form>

        {{-- Form Penolakan --}}
        <form action="{{ route('pengurus.pinjaman.reject', $pinjaman->id) }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                ❌ Tolak
            </button>
        </form>

    </div>
@endsection
