@extends('Pengurus.index')

@section('content')
    <div class="p-6 bg-white rounded shadow">
        <h2 class="text-xl font-bold mb-4">Detail Angsuran - Pinjaman ID: {{ $pinjaman->id }}</h2>

        <form action="{{ route('pengurus.pinjaman.updateStatus', $pinjaman->id) }}" method="POST">
            @csrf
            @method('PUT')

            <table class="min-w-full border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 text-center">
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Bulan Ke</th>
                        <th class="px-4 py-2 border">Jumlah Bayar</th>
                        <th class="px-4 py-2 border">Diskon</th>
                        <th class="px-4 py-2 border">Tanggal Bayar</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($angsuran as $index => $item)
                        <tr class="text-center">
                            <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 border">{{ $item->bulan_ke }}</td>
                            <td class="px-4 py-2 border">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border">
                                @if($item->diskon > 0)
                                    <span class="text-green-600">Rp {{ number_format($item->diskon, 0, ',', '.') }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-2 border">{{ $item->tanggal_bayar ?? '-' }}</td>
                            <td class="px-4 py-2 border">
                                @if ($item->status == 'lunas')
                                    <span class="text-green-600 font-semibold">Lunas</span>
                                @else
                                    <span class="text-red-600 font-semibold">Belum Lunas</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border">
                                <input type="checkbox" name="angsuran_ids[]" value="{{ $item->id }}"
                                    {{ $item->status == 'lunas' ? 'checked disabled' : '' }}>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-2 border text-center text-gray-500">
                                Belum ada data angsuran untuk pinjaman ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4 flex justify-between">
                <a href="{{ route('pengurus.pinjaman.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded">
                    Kembali
                </a>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
@endsection
