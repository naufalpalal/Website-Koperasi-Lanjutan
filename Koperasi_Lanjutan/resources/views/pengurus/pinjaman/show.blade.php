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

        <h3 class="mt-4 font-semibold">Dokumen:</h3>
        @if ($pinjaman->dokumen_pinjaman)
            <a href="{{ asset('storage/' . $pinjaman->dokumen_pinjaman) }}" target="_blank"
                class="text-blue-600 underline hover:text-blue-800">
                📄 Lihat Dokumen Pinjaman
            </a>
        @else
            <p class="text-gray-500 italic">Tidak ada dokumen yang diunggah.</p>
        @endif

        {{-- Form Persetujuan --}}
        <form action="{{ route('pengurus.pinjaman.approve', $pinjaman->id) }}" method="POST" class="mt-6">
            @csrf
            <label class="block mb-1 font-semibold">Bunga (%):</label>
            <input type="number" name="bunga" step="0.1" min="0" required
                class="border p-2 rounded w-full focus:outline-none focus:ring focus:ring-green-300">

            <label class="block mt-3 mb-1 font-semibold">Tenor (bulan):</label>
            <input type="number" name="tenor" min="1" max="36" required
                class="border p-2 rounded w-full focus:outline-none focus:ring focus:ring-green-300">

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
