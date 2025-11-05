@extends('pengurus.index')

@section('title', 'Kelola Tabungan')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3">
        <h2 class="text-2xl font-bold text-gray-800">Kelola Tabungan Anggota</h2>

        {{-- Form Pencarian --}}
        <form method="GET" action="{{ route('pengurus.tabungan.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama anggota..."
                   class="border border-gray-300 rounded-lg px-4 py-2 w-full sm:w-64 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full sm:w-auto transition duration-200">
                Cari
            </button>
        </form>
    </div>

    {{-- Pesan sukses --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm sm:text-base">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        {{-- Scroll horizontal di layar kecil --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-left border border-gray-200 text-sm sm:text-base">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-3 whitespace-nowrap">No</th>
                        <th class="px-4 py-3 whitespace-nowrap">Nama Anggota</th>
                        <th class="px-4 py-3 whitespace-nowrap">Total Tabungan</th>
                        <th class="px-4 py-3 text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tabungans as $index => $item)
                        <tr class="border-b hover:bg-gray-50 transition duration-200">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">{{ $item->nama }}</td>
                            <td class="px-4 py-3 font-semibold text-green-600">
                                Rp {{ number_format($item->total_saldo ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <a href="{{ route('pengurus.tabungan.debit', $item->id) }}" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs sm:text-sm">
                                        Debit
                                    </a>
                                    <a href="{{ route('pengurus.tabungan.kredit', $item->id) }}" 
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs sm:text-sm">
                                        Kredit
                                    </a>
                                    <a href="{{ route('pengurus.tabungan.detail', $item->id) }}" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 rounded-lg text-xs sm:text-sm">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500">Belum ada data anggota</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $tabungans->links() }}
        </div>
    </div>
</div>
@endsection