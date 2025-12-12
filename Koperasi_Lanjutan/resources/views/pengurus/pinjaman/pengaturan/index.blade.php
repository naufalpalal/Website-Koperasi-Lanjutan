@extends('Pengurus.index')

@section('content')
    <div class="p-6 bg-white rounded shadow">

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between mb-6">
            <h2 class="text-2xl font-bold">Daftar Paket Pinjaman</h2>

            <a href="{{ route('pengurus.pinjaman.settings.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                + Tambah Paket
            </a>
        </div>

        <table class="w-full border rounded">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border">Nama Paket</th>
                    <th class="p-2 border">Nominal</th>
                    <th class="p-2 border">Tenor</th>
                    <th class="p-2 border">Bunga</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($pakets as $p)
                    <tr>
                        <td class="border p-2">{{ $p->nama_paket }}</td>
                        <td class="border p-2">Rp {{ number_format($p->nominal) }}</td>
                        <td class="border p-2">{{ $p->tenor }} bulan</td>
                        <td class="border p-2">{{ rtrim(rtrim(number_format($p->bunga, 2), '0'), '.') }}%</td>


                        <td class="border p-2">
                            @if($p->status)
                                <span class="text-green-600 font-bold">Aktif</span>
                            @else
                                <span class="text-red-600 font-bold">Nonaktif</span>
                            @endif
                        </td>

                        <td class="border p-2 text-center">

                            {{-- Toggle --}}
                            <form action="{{ route('pengurus.pinjaman.paket.toggle', $p->id) }}" method="POST"
                                class="inline-block">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded text-white
                                          {{ $p->status ? 'bg-red-500' : 'bg-green-500' }}">
                                    {{ $p->status ? 'Matikan' : 'Aktifkan' }}
                                </button>
                            </form>

                            {{-- Hapus --}}
                            <form action="{{ route('pengurus.pinjaman.paket.delete', $p->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus paket ini?');" class="inline-block ml-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 rounded text-white bg-red-700">
                                    Hapus
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection