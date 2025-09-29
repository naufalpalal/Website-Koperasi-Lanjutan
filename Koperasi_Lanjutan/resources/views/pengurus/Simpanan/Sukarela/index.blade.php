@extends('pengurus.index')

@section('content')
    <div class="p-6 bg-white rounded shadow">
        <h2 class="text-xl font-bold mb-4">Persetujuan Simpanan Sukarela</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @php
            // Fallback periode: old() -> variabel dari controller -> bulan ini
            $selectedPeriod = old('bulan', $bulan ?? now()->format('Y-m'));
        @endphp

        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <form action="{{ route('pengurus.simpanan.sukarela.generate') }}" method="POST"
                class="flex items-center space-x-3">
                @csrf

                <div>
                    <label for="bulan" class="block text-sm font-medium text-gray-700">Pilih Periode</label>
                    <input type="month" name="bulan" id="bulan" value="{{ $selectedPeriod }}"
                        class="mt-1 border rounded px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none">
                </div>

                <div class="pt-5">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                        Generate Simpanan Sukarela
                    </button>
                </div>
            </form>
        </div>
        <form action="{{ route('pengurus.simpanan.sukarela.update') }}" method="POST">
            @csrf
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border">Pilih</th>
                        <th class="px-4 py-2 border">Nama Anggota</th>
                        <th class="px-4 py-2 border">Bulan</th>
                        <th class="px-4 py-2 border">Tahun</th>
                        <th class="px-4 py-2 border">Nominal</th>
                        <th class="px-4 py-2 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($simpanan as $item)
                        <tr>
                            <td class="px-4 py-2 border text-center">
                                <input type="checkbox" name="ids[]" value="{{ $item->id }}">
                            </td>
                            <td class="px-4 py-2 border">{{ $item->user->nama ?? '-' }}</td>
                            <td class="px-4 py-2 border">{{ $item->bulan }}</td>
                            <td class="px-4 py-2 border">{{ $item->tahun }}</td>
                            <td class="px-4 py-2 border">Rp {{ number_format($item->nilai, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border">{{ $item->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Simpan Persetujuan
                </button>
            </div>
        </form>
        <form action="{{ route('pengurus.simpanan.sukarela.pengajuan') }}" method="GET" class="mt-4">
            @csrf
            <div>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Lihat Pengajuan Baru
                </button>
            </div>
        </form>



    </div>
@endsection
