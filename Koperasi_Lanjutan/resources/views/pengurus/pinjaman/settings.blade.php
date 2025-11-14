@extends('Pengurus.index')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Setting Tenor & Bunga Pinjaman</h2>

    {{-- Form tambah tenor --}}
    <form action="{{ route('pengurus.settings.store') }}" method="POST">
        @csrf

        <label class="block mt-2 font-semibold">Tenor (bulan)</label>
        <input type="number" name="tenor" class="border p-2 rounded w-full">

        <label class="block mt-2 font-semibold">Bunga (% per bulan)</label>
        <input type="number" step="0.1" name="bunga" class="border p-2 rounded w-full">

        <button class="bg-blue-600 text-white px-4 py-2 rounded mt-3">Tambah</button>
    </form>

    <h3 class="mt-6 font-bold">Daftar Tenor & Bunga Tersedia</h3>
    <table class="w-full mt-2 border">
        <tr class="bg-gray-200">
            <th class="p-2">Tenor</th>
            <th class="p-2">Bunga</th>
        </tr>

        @foreach ($settings as $set)
        <tr>
            <td class="border p-2">{{ $set->tenor }} bulan</td>
            <td class="border p-2">{{ $set->bunga }}%</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
