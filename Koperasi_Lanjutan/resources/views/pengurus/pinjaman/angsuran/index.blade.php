@extends('Pengurus.index')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Detail Angsuran - Pinjaman ID: {{ $pinjaman->id }}</h2>

    <table class="min-w-full border border-gray-300">
        <thead>
            <tr class="bg-gray-100 text-center">
                <th class="px-4 py-2 border">No</th>
                <th class="px-4 py-2 border">Bulan Ke</th>
                <th class="px-4 py-2 border">Jumlah Bayar</th>
                <th class="px-4 py-2 border">Tanggal Bayar</th>
                <th class="px-4 py-2 border">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($angsuran as $index => $item)
                <tr class="text-center">
                    <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 border">{{ $item->bulan_ke }}</td>
                    <td class="px-4 py-2 border">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 border">{{ $item->tanggal_bayar ?? '-' }}</td>
                    <td class="px-4 py-2 border">
                        @if($item->status == 'lunas')
                            <span class="text-green-600 font-semibold">Lunas</span>
                        @else
                            <span class="text-red-600 font-semibold">Belum Lunas</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-2 border text-center text-gray-500">
                        Belum ada data angsuran untuk pinjaman ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        <a href="{{ route('pengurus.pinjaman.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded">
            Kembali
        </a>
    </div>
</div>
@endsection
