@extends('user.index')

@section('title', 'Tabungan')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-10">

    <div class="bg-white shadow-lg rounded-xl p-6 w-full max-w-xl mx-auto mb-10">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6 text-center sm:text-left">Tambah Tabungan</h2>

        {{-- PESAN ERROR --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tahap 1 --}}
        @if (!$showQr)
            <form action="{{ route('tabungan.index') }}" method="GET" class="space-y-4">
                <div>
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Transfer</label>
                    <input type="date" id="tanggal" name="tanggal"
                        value="{{ old('tanggal', date('Y-m-d')) }}"
                        min="{{ date('Y-m-d') }}"
                        pattern="\d{4}-\d{2}-\d{2}"
                        title="Gunakan format tanggal yang benar (YYYY-MM-DD)"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm px-3 py-2" required>
                </div>

                <div>
                    <label for="nilai" class="block text-sm font-medium text-gray-700">Nominal Tabungan (Rp)</label>
                    <input type="number" id="nilai" name="nilai"
                           value="{{ old('nilai') }}" min="100" step="100"
                           placeholder="Minimal 100"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm px-3 py-2" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" name="show_qr" value="1"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition w-full sm:w-auto">
                        Ajukan Tabungan
                    </button>
                </div>
            </form>

        @else
            {{-- Tahap 2 --}}
            <form action="{{ route('user.simpanan.tabungan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <input type="hidden" name="nilai" value="{{ $nilai }}">

                <p class="text-gray-700 mb-2 text-center">Silakan scan QR berikut untuk transfer:</p>

                <div class="flex justify-center">
                    <img src="{{ asset('assets/moh.naufal16_qr.png') }}" alt="QR Code"
                        class="w-40 h-40 border rounded-lg shadow">
                </div>

                <div>
                    <label for="bukti_transfer" class="block text-sm font-medium text-gray-700 mb-1">
                        Upload Bukti Transfer
                    </label>
                    <input type="file" id="bukti_transfer" name="bukti_transfer"
                           accept="image/png, image/jpeg, image/jpg"
                           class="block w-full text-sm text-gray-700 border rounded-lg p-2" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition w-full sm:w-auto">
                        Kirim Bukti Transfer
                    </button>
                </div>
            </form>
        @endif
    </div>

    {{-- Riwayat --}}
    <div class="bg-white shadow-md rounded-xl p-6 mt-10">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3 border-b pb-4">
            <h2 class="text-xl sm:text-2xl font-semibold text-gray-700">Riwayat Tabungan Terbaru</h2>

            <a href="{{ route('tabungan.history') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition text-sm w-full sm:w-auto text-center">
                Lihat Semua Riwayat
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm">{{ session('success') }}</div>
        @endif

        {{-- Responsif: table scroll di mobile --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 rounded-lg text-sm">
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
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
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
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">Pending</span>
                                @elseif ($item->status == 'diterima')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">Diterima</span>
                                @elseif ($item->status == 'dipotong')
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">Dipotong</span>
                                @elseif ($item->status == 'ditolak')
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500 text-sm">Belum ada transaksi tabungan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection