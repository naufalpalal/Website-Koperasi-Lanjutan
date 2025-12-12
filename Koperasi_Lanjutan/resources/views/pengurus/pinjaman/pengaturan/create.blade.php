@extends('Pengurus.index')

@section('content')
<div class="p-6 bg-white rounded shadow">

    <h2 class="text-2xl font-bold mb-6">Tambah Paket Pinjaman</h2>

    <form action="{{ route('pengurus.pinjaman.settings.store') }}"
          method="POST"
          onsubmit="return confirm('Yakin ingin membuat paket pinjaman baru?');">
        @csrf

        <label class="block font-semibold mt-3">Nama Paket</label>
        <input type="text" name="nama_paket"
               class="border p-2 rounded w-full"
               placeholder="Contoh: Paket A" required>

        <label class="block font-semibold mt-3">Nominal Pinjaman</label>
        <input type="number" name="nominal"
               class="border p-2 rounded w-full"
               placeholder="Contoh: 5000000" required>

        <label class="block font-semibold mt-3">Tenor (bulan)</label>
        <input type="number" name="tenor"
               class="border p-2 rounded w-full"
               min="1" max="36" required>

        <label class="block font-semibold mt-3">Bunga (% per bulan)</label>
        <input type="number" step="0.1" name="bunga"
               class="border p-2 rounded w-full" required>

        <button class="bg-blue-600 text-white px-4 py-2 rounded mt-4">
            Simpan
        </button>

        <a href="{{ route('pengurus.pinjaman.settings.index') }}"
           class="ml-2 bg-gray-500 text-white px-4 py-2 rounded mt-4 inline-block">
            Kembali
        </a>
    </form>

</div>
@endsection
