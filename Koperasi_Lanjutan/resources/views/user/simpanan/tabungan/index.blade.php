@extends('user.index')

@section('title', 'Tabungan')

@section('content')
<div class="container mx-auto px-6 py-10">
    <div class="bg-white shadow-lg rounded-xl p-6 max-w-lg mx-auto mb-10">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Tambah Tabungan</h2>

        {{-- TAMPILKAN ERROR --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tahap 1: Isi nominal & tanggal --}}
        @if (!$showQr)
            <form action="{{ route('user.simpanan.tabungan.index') }}" method="GET" class="space-y-4">
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Transfer</label>
                    <input type="date" id="tanggal" name="tanggal"
                           value="{{ old('tanggal', date('Y-m-d')) }}"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm px-3 py-2" required>
                </div>

                <div>
                    <label for="nilai" class="block text-sm font-medium text-gray-700">Nominal Tabungan (Rp)</label>
                    <input type="number" id="nilai" name="nilai"
                           value="{{ old('nilai') }}" min="1000" step="1000"
                           placeholder="Masukkan nominal (contoh: 100000)"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm px-3 py-2" required>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" name="show_qr" value="1"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition">
                        Ajukan Tabungan
                    </button>
                </div>
            </form>
        @else
        {{-- Tahap 2: Tampilkan QR dan upload bukti --}}
            <form action="{{ route('user.simpanan.tabungan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <input type="hidden" name="nilai" value="{{ $nilai }}">

                <p class="text-gray-700 mb-2 text-center">Silakan scan QR berikut untuk transfer:</p>
                <img src="{{ asset('assets/moh.naufal16_qr.png') }}" alt="QR Code"
                     class="mx-auto w-40 h-40 border rounded-lg shadow">

                <div class="mt-4">
                    <label for="bukti_transfer" class="block text-sm font-medium text-gray-700 mb-1">
                        Upload Bukti Transfer
                    </label>
                    <input type="file" id="bukti_transfer" name="bukti_transfer" accept="image/*"
                           class="block w-full text-sm text-gray-700 border rounded-lg p-2" required>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
                        Kirim Bukti Transfer
                    </button>
                </div>
            </form>
        @endif
    </div>

    {{-- DAFTAR TABUNGAN --}}
    <div class="bg-white shadow-lg rounded-xl p-6">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Daftar Tabungan</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        <table class="w-full border border-gray-300 rounded-lg">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">No</th>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Jumlah</th>
                    <th class="px-4 py-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tabungans as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td class="px-4 py-2">Rp {{ number_format($item->nilai, 0, ',', '.') }}</td>
                        <td class="px-4 py-2">
                            @if ($item->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">Pending</span>
                            @elseif ($item->status == 'diterima')
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Diterima</span>
                            @elseif ($item->status == 'ditolak')
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">Belum ada tabungan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection