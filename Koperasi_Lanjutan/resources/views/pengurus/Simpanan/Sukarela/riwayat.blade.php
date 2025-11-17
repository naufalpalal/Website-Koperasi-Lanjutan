@extends('pengurus.index')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Riwayat Simpanan Sukarela</h2>
        {{-- Form Filter --}}
        <form action="{{ route('pengurus.simpanan.sukarela.riwayat') }}" method="GET">
            <div
                class="bg-gradient-to-br rounded-lg shadow-md p-4 flex flex-col items-center justify-center mb-4 w-full sm:w-auto">
                <h3 class="text-base font-semibold text-center">
                    Total Simpanan:
                    <span class="text-green-600">
                        Rp {{ number_format($totalSimpanan, 0, ',', '.') }}
                    </span>
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 bg-white p-4 rounded-lg shadow-sm">
                {{-- Filter Anggota (ID) --}}
                <div>
                    <label for="id" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">ID Anggota
                        (Opsional)</label>
                    <input type="text" name="id" id="id"
                        class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        value="{{ $id }}">
                </div>

                {{-- Filter Bulan --}}
                <div>
                    <label for="bulan" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Bulan</label>
                    <select name="bulan" id="bulan"
                        class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Semua Bulan --</option>
                        @foreach (range(1, 12) as $b)
                            <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Tahun --}}
                <div>
                    <label for="tahun" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Tahun</label>
                    <select name="tahun" id="tahun"
                        class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Semua Tahun --</option>
                        @foreach (range(date('Y'), date('Y') - 5) as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol --}}
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                        Filter
                    </button>
                    <a href="{{ route('pengurus.simpanan.sukarela.riwayat') }}"
                        class="px-3 py-1.5 bg-gray-300 text-gray-800 rounded-lg text-sm hover:bg-gray-400 transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Tabel Riwayat --}}
        <div class="mt-4 bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs sm:text-sm font-semibold">No</th>
                        <th class="px-3 py-2 text-left text-xs sm:text-sm font-semibold">Nama Anggota</th>
                        <th class="px-3 py-2 text-left text-xs sm:text-sm font-semibold">Bulan</th>
                        <th class="px-3 py-2 text-left text-xs sm:text-sm font-semibold">Tahun</th>
                        <th class="px-3 py-2 text-left text-xs sm:text-sm font-semibold">Tanggal Simpan</th>
                        <th class="px-3 py-2 text-left text-xs sm:text-sm font-semibold">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($riwayat as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 text-xs sm:text-sm text-gray-700">{{ $index + $riwayat->firstItem() }}</td>
                            <td class="px-3 py-2 text-xs sm:text-sm text-gray-700">{{ $item->user->nama ?? 'Anggota' }}</td>
                            <td class="px-3 py-2 text-xs sm:text-sm text-gray-700">
                                {{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }} - {{ $item->tahun }}
                            </td>
                            <td class="px-3 py-2 text-xs sm:text-sm font-semibold text-green-600">Rp
                                {{ number_format($item->nilai, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-3 text-center text-gray-500 italic text-sm">
                                Tidak ada data riwayat simpanan
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $riwayat->links('pagination::tailwind') }}
        </div>
    </div>
@endsection