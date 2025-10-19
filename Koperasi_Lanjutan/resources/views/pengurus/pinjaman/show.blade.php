@extends('Pengurus.index')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Detail Pengajuan Pinjaman</h2>

    <p><strong>Nama:</strong> {{ $pinjaman->user->nama }}</p>
    <p><strong>Nominal:</strong> Rp{{ number_format($pinjaman->nominal, 0, ',', '.') }}</p>

    <h3 class="mt-4 font-semibold">Dokumen:</h3>
    <ul>
        @foreach($pinjaman->dokumen as $dok)
            <li><a href="{{ asset('storage/'.$dok->file_path) }}" target="_blank" class="text-blue-600">Lihat Dokumen</a></li>
        @endforeach
    </ul>

    <form action="{{ route('pengurus.pinjaman.approve', $pinjaman->id) }}" method="POST" class="mt-4">
        @csrf
        <label>Bunga (%):</label>
        <input type="number" name="bunga" step="0.1" min="0" required class="border p-2 rounded w-full">

        <label class="mt-2">Tenor (bulan):</label>
        <input type="number" name="tenor" min="1" max="10" required class="border p-2 rounded w-full">

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded mt-3">Setujui</button>
    </form>

    <form action="{{ route('pengurus.pinjaman.reject', $pinjaman->id) }}" method="POST" class="mt-2">
        @csrf
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Tolak</button>
    </form>
</div>
@endsection
