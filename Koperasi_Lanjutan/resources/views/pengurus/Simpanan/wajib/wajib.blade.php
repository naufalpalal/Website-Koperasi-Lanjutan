@extends('admin.index')
@section('title', 'Simpanan Wajib')
@extends('admin.layouts.navbar')
@section('content')
    <div class="container mx-auto px-4 pt-12">
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-2xl font-semibold text-gray-700 mb-6">Transaksi Simpanan Wajib Bulanan</h1>

            {{-- Filter Bulan --}}
            <form method="GET" action="{{ route('admin.simpanan.wajib.wajib') }}" class="mb-6 flex items-center gap-3">
                <label for="bulan" class="text-gray-700 font-medium">Pilih Bulan:</label>
                <div class="relative">
                    <select name="bulan" id="bulan"
                        class="appearance-none border border-gray-300 rounded-lg px-4 py-2 pr-10 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($bulanList as $b)
                            <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($b)->translatedFormat('F Y') }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Icon dropdown --}}
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition">
                    Lihat
                </button>
            </form>

            {{-- Tabel Simpanan Wajib --}}
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full text-sm text-left border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">Anggota</th>
                            <th class="px-4 py-2 border">Simpanan Wajib</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $memberId => $simpanans)
                            @php
                                $member = $simpanans->first()->member;
                                $wajib = $simpanans->where('type', 'wajib')->first();
                            @endphp
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $member->nama }}</td>
                                <td class="px-4 py-2">
                                    Rp {{ number_format($wajib->amount ?? 0, 0, ',', '.') }}
                                    <span
                                        class="ml-2 px-2 py-1 rounded text-xs
                                        {{ $wajib && $wajib->status == 'success' ? 'bg-green-100 text-green-700' : ($wajib && $wajib->status == 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ $wajib->status ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    @if ($wajib)
                                        <a href="{{ route('admin.simpanan.edit', $wajib->id) }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm shadow">
                                            Update
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-gray-500">
                                    Tidak ada data simpanan wajib untuk bulan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Tombol Generate hanya untuk bulan sekarang --}}
            @if ($bulan === now()->format('Y-m-01'))
                <form action="{{ route('admin.simpanan.generate') }}" method="POST" class="mt-6">
                    @csrf
                    <button
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow transition font-medium">
                        + Generate Simpanan Wajib Bulan Ini
                    </button>
                </form>
            @endif
        </div>

        <form action="{{ route('admin.wajib.store') }}" method="POST" class="mb-6">
            @csrf
            <label for="amount" class="font-medium">Nominal Baru:</label>
            <input type="number" name="amount" class="border px-3 py-2 rounded" required>

            <label for="start_date" class="ml-4 font-medium">Berlaku Mulai:</label>
            <input type="month" name="start_date" class="border px-3 py-2 rounded" required>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded ml-4">
                Simpan Aturan
            </button>
        </form>

    </div>
@endsection
