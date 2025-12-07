@extends('user.index')

@section('content')

    {{-- ============================= --}}
    {{-- CARD ATAS: INFO SIMPANAN --}}
    {{-- ============================= --}}
    <div class="p-6 bg-white rounded shadow mb-6">

        <h2 class="text-xl font-bold mb-4">Simpanan Sukarela</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Total Simpanan --}}
            <div class="p-4 bg-blue-50 border border-blue-200 rounded">
                <p class="text-sm text-gray-600">Total Simpanan</p>
                <p class="text-2xl font-bold text-blue-700">
                    Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- TOMBOL AKSI (nanti kamu custom sendiri) --}}
        <div class="mt-5 flex gap-3">
            <!-- Tombol Ajukan Perubahan Nominal -->
            <a href="{{ route('user.simpanan.sukarela.pengajuan') }}"
                class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition font-medium">
                Ajukan Perubahan Nominal
            </a>

            <!-- Tombol Ajukan Libur -->
            <a
                class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition font-medium">
                Ajukan Libur
            </a>
        </div>


    </div>

    {{-- ============================= --}}
    {{-- CARD BAWAH: RIWAYAT PEMOTONGAN --}}
    {{-- ============================= --}}
    <div class="p-6 bg-white rounded shadow">

        <h2 class="text-xl font-bold mb-4">Riwayat Pemotongan Simpanan Sukarela</h2>

        {{-- FILTER --}}
        <form method="GET" action="{{ route('user.simpanan.sukarela.index') }}"
            class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            {{-- Filter Bulan --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Bulan</label>
                <select name="bulan" class="w-full border rounded p-2">
                    <option value="">Semua</option>
                    @foreach(range(1, 12) as $b)
                        <option value="{{ $b }}" {{ (string) request('bulan') === (string) $b ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($b)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tahun --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Tahun</label>
                <select name="tahun" class="w-full border rounded p-2">
                    <option value="">Semua</option>
                    @foreach(($tahunList ?? []) as $th)
                        <option value="{{ $th }}" {{ (string) request('tahun') === (string) $th ? 'selected' : '' }}>
                            {{ $th }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol --}}
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
                    Filter
                </button>
            </div>

            <div class="flex items-end">
                <a href="{{ route('user.simpanan.sukarela.index') }}"
                    class="w-full p-2 text-center border rounded hover:bg-gray-100">
                    Reset
                </a>
            </div>
        </form>

        {{-- TABEL RIWAYAT --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border text-left">Bulan</th>
                        <th class="px-4 py-2 border text-left">Tahun</th>
                        <th class="px-4 py-2 border text-right">Nominal</th>
                        <th class="px-4 py-2 border text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border">
                                {{ \Carbon\Carbon::create()->month($item->bulan)->format('F') }}
                            </td>
                            <td class="px-4 py-2 border">{{ $item->tahun }}</td>
                            <td class="px-4 py-2 border text-right">
                                Rp {{ number_format($item->nilai, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2 border">
                                @php
                                    $status = strtolower($item->status ?? '');
                                @endphp
                                <span
                                    class="{{ $status === 'sudah' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">
                                    {{ $status ? ucfirst($status) : 'Belum' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Belum ada riwayat pemotongan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-4">
            @if(method_exists($data, 'links'))
                {{ $data->withQueryString()->links() }}
            @endif
        </div>

    </div>

@endsection