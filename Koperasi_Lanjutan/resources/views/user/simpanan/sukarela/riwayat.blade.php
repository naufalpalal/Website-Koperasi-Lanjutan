@extends('user.index')

@section('content')
<div class="p-6 bg-white rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Riwayat Simpanan Sukarela</h2>

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">Tahun</th>
                <th class="border px-4 py-2">Bulan</th>
                <th class="border px-4 py-2">Nilai</th>
                <th class="border px-4 py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $item)
                <tr>
                    <td class="border px-4 py-2">{{ $item->tahun }}</td>
                    <td class="border px-4 py-2">{{ $item->bulan }}</td>
                    <td class="border px-4 py-2">Rp{{ number_format($item->nilai, 0, ',', '.') }}</td>
                    <td class="border px-4 py-2">{{ $item->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-gray-500 py-4">Belum ada riwayat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
