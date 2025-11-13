@extends('user.index')

@section('title', 'Riwayat Tabungan Lengkap')

@section('content')
<div class="container mx-auto px-6 py-10">
    <div class="bg-white shadow-lg rounded-xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-700">Riwayat Tabungan Lengkap</h2>
            <a href="{{ route('tabungan.index') }}" 
               class="text-blue-600 hover:text-blue-800 text-sm">← Kembali</a>
        </div>

        {{-- Filter Data --}}
<form method="GET" class="bg-white rounded-xl p-4 mb-6 border border-gray-200 shadow-sm">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        {{-- Input Tanggal --}}
        <div>
            <label for="tanggal" class="block text-sm font-medium text-gray-600 mb-1">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal"
                value="{{ $tanggal ?? '' }}"
                class="w-full border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring focus:ring-blue-200 focus:border-blue-400 transition">
        </div>

        {{-- Filter Status --}}
        <div>
            <label for="status" class="block text-sm font-medium text-gray-600 mb-1">Status</label>
            <select name="status" id="status"
                class="w-full border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:ring focus:ring-blue-200 focus:border-blue-400 transition">
                <option value="">Semua Status</option>
                <option value="diterima" {{ ($status ?? '') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="ditolak" {{ ($status ?? '') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                <option value="dipotong" {{ ($status ?? '') == 'dipotong' ? 'selected' : '' }}>Dipotong</option>
            </select>
        </div>

        {{-- Tombol Filter --}}
        <div>
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition-all duration-200">
                Filter
            </button>
        </div>
    </div>
</form>


        <table class="w-full border border-gray-300 rounded-lg">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">No</th>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Keterangan</th>
                    <th class="px-4 py-2 text-left">Jumlah</th>
                    <th class="px-4 py-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tabungans as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $loop->iteration + ($tabungans->currentPage() - 1) * $tabungans->perPage() }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td class="px-4 py-2">
                            @if ($item->status == 'dipotong')
                                <span class="text-red-600 font-semibold">Dana Diambil</span>
                            @else
                                <span class="text-green-600 font-semibold">Dana Masuk</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if ($item->status == 'dipotong')
                                <span class="text-red-600">- Rp {{ number_format($item->debit, 0, ',', '.') }}</span>
                            @else
                                <span class="text-green-600">+ Rp {{ number_format($item->nilai, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @if ($item->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">Pending</span>
                            @elseif ($item->status == 'diterima')
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Diterima</span>
                            @elseif ($item->status == 'dipotong')
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">Dipotong</span>
                            @elseif ($item->status == 'ditolak')
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-semibold">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Belum ada transaksi tabungan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $tabungans->links() }}
        </div>
    </div>
</div>
@endsection