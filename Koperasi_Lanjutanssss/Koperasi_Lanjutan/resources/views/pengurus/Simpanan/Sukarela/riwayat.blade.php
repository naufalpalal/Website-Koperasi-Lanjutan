@extends('pengurus.index')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Riwayat Simpanan Sukarela</h2>

    {{-- Form Filter --}}
    <form action="{{ route('pengurus.simpanan.sukarela.riwayat') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-5 rounded-2xl shadow-sm">
        {{-- Filter Anggota (ID) --}}
        <div>
            <label for="id" class="block text-sm font-semibold text-gray-700 mb-1">ID Anggota (Opsional)</label>
            <input type="text" name="id" id="id" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ $id }}">
        </div>

        {{-- Filter Bulan --}}
        <div>
            <label for="bulan" class="block text-sm font-semibold text-gray-700 mb-1">Bulan</label>
            <select name="bulan" id="bulan" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
            <label for="tahun" class="block text-sm font-semibold text-gray-700 mb-1">Tahun</label>
            <select name="tahun" id="tahun" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Semua Tahun --</option>
                @foreach (range(date('Y'), date('Y') - 5) as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tombol --}}
        <div class="flex items-end space-x-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Filter
            </button>
            <a href="{{ route('pengurus.simpanan.sukarela.riwayat') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                Reset
            </a>
        </div>
    </form>

    {{-- Informasi Anggota --}}
    @if($anggota)
        <div class="mt-4 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg">
            <strong>Anggota:</strong> {{ $anggota->name }} (ID: {{ $anggota->id }})
        </div>
    @endif

    {{-- Tabel Riwayat --}}
    <div class="mt-6 bg-white rounded-2xl shadow overflow-x-auto">
        <table class="min-w-full border-collapse">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">No</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Nama Anggota</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Bulan</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Tahun</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Tanggal Simpan</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($riwayat as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $index + $riwayat->firstItem() }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $item->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $item->tahun }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-green-600">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500 italic">Tidak ada data riwayat simpanan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $riwayat->links('pagination::tailwind') }}
    </div>
</div>
@endsection
