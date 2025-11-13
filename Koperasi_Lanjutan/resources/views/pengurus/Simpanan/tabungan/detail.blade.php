@extends('pengurus.index')

@section('title', 'Detail Tabungan Anggota')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-8">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-3">
        <h2 class="text-2xl font-bold text-gray-800">
            Detail Tabungan: {{ $user->nama }}
        </h2>
        <a href="{{ route('pengurus.tabungan.index') }}" 
           class="text-blue-600 hover:text-blue-800 text-sm text-left sm:text-right">← Kembali ke Daftar</a>
    </div>

    {{-- Total saldo --}}
    <div class="bg-white shadow-lg rounded-xl p-5 sm:p-6 mb-6 text-center sm:text-left">
        <h3 class="text-lg font-semibold text-gray-700">Total Saldo</h3>
        <p class="text-2xl font-bold text-green-600 mt-2 break-words">
            Rp {{ number_format($totalSaldo, 0, ',', '.') }}
        </p>
    </div>

    {{-- Filter Data --}}
    <form method="GET" class="bg-white shadow-md rounded-2xl p-5 mb-6 border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-4 md:space-y-0">
            {{-- Input Tanggal --}}
            <div class="flex-1 relative">
                <label for="tanggal" class="block text-sm font-medium text-gray-600 mb-1">Tanggal</label>
                <input 
                    type="date" 
                    name="tanggal" 
                    min="2000-01-01" 
                    max="{{ date('Y-m-d') }}"
                    value="{{ request('tanggal', old('tanggal')) }}"
                    class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none"
                >
                @error('tanggal')
                    <p class="text-red-500 text-sm mt-1 absolute">{{ $message }}</p>
                @enderror
            </div>

            {{-- Filter Status --}}
            <div class="flex-1">
                <label for="status" class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                <select name="status" id="status"
                    class="w-full border-gray-300 rounded-xl px-4 py-2.5 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">Semua Status</option>
                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="dipotong" {{ request('status') == 'dipotong' ? 'selected' : '' }}>Dipotong</option>
                </select>
            </div>

            {{-- Tombol Filter --}}
            <div class="flex items-center justify-center">
                <button type="submit"
                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-medium shadow-md hover:shadow-lg transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 13.414V20a1 1 0 01-1.447.894l-4-2A1 1 0 018 18v-4.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    <span>Filter</span>
                </button>
            </div>
        </div>
    </form>

    {{-- Riwayat Tabungan --}}
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <h3 class="text-lg font-semibold text-gray-700 p-4 border-b">Riwayat Tabungan</h3>

        {{-- Wrapper agar tabel bisa discroll di HP --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-left border-t text-sm sm:text-base">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-3 whitespace-nowrap">No</th>
                        <th class="px-4 py-3 whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3 whitespace-nowrap">Nominal</th>
                        <th class="px-4 py-3 whitespace-nowrap">Bukti Transfer</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tabungans as $index => $item)
                        <tr class="border-b hover:bg-gray-50 transition duration-200">
                            <td class="px-4 py-3">{{ ($tabungans->currentPage() - 1) * $tabungans->perPage() + $index + 1 }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>

                            {{-- Nominal --}}
                            <td class="px-4 py-3 font-semibold {{ $item->status === 'dipotong' ? 'text-red-600' : 'text-green-600' }}">
                                {{ $item->status === 'dipotong' ? '- ' : '+ ' }}Rp 
                                {{ number_format($item->status === 'dipotong' ? $item->debit : $item->nilai, 0, ',', '.') }}
                            </td>

                            {{-- Bukti Transfer --}}
                            <td class="px-4 py-3">
                                @if ($item->bukti_transfer)
                                    <div class="flex justify-center">
                                        <a href="{{ asset('uploads/bukti_transfer/' . $item->bukti_transfer) }}" target="_blank">
                                            <img src="{{ asset('uploads/bukti_transfer/' . $item->bukti_transfer) }}"
                                                alt="Bukti Transfer"
                                                class="w-14 h-14 sm:w-16 sm:h-16 object-cover rounded shadow hover:scale-105 transition-transform duration-200">
                                        </a>
                                    </div>
                                @elseif($item->status === 'dipotong')
                                    <span class="text-red-600 font-semibold italic">Pemotongan</span>
                                @else
                                    <span class="text-green-600 font-semibold italic">Dibayar langsung</span>
                                @endif
                            </td>

                            {{-- Status + Aksi --}}
                            <td class="px-4 py-3 text-center">
                                <div class="@if($item->status === 'diterima') text-green-600 
                                            @elseif($item->status === 'pending') text-yellow-600 
                                            @elseif($item->status === 'dipotong') text-red-600 
                                            @else text-gray-600 @endif font-semibold mb-1">
                                    {{ ucfirst($item->status) }}
                                </div>

                                @if ($item->status === 'pending')
                                    <div class="flex flex-wrap justify-center gap-2 mt-2">
                                        <form action="{{ route('pengurus.tabungan.terima', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" 
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-xs sm:text-sm font-medium transition">
                                                Terima
                                            </button>
                                        </form>
                                        <form action="{{ route('pengurus.tabungan.tolak', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs sm:text-sm font-medium transition">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">Belum ada transaksi tabungan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4">
            {{ $tabungans->appends(request()->all())->links() }}
        </div>
    </div>
</div>
@endsection