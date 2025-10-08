@extends('user.index')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Riwayat Simpanan Sukarela</h2>

    <table class="min-w-full border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 border">Bulan</th>
                <th class="px-4 py-2 border">Tahun</th>
                <th class="px-4 py-2 border">Nominal</th>
                <th class="px-4 py-2 border">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $item)
            <tr>
                <td class="px-4 py-2 border">{{ $item->bulan }}</td>
                <td class="px-4 py-2 border">{{ $item->tahun }}</td>
                <td class="px-4 py-2 border">Rp {{ number_format($item->nilai, 0, ',', '.') }}</td>
                <td class="px-4 py-2 border">{{ $item->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4">Belum ada riwayat simpanan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
