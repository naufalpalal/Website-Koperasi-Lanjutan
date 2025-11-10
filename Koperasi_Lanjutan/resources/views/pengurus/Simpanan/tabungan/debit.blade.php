@extends('pengurus.index')

@section('title', 'Detail Tabungan Anggota')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-8">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-3">
        <h2 class="text-2xl font-bold text-gray-800">
            Debit Tabungan: {{ $user->nama }}
        </h2>
        <a href="{{ route('pengurus.tabungan.index') }}" 
           class="text-blue-600 hover:text-blue-800 text-sm text-right sm:text-left">← Kembali ke Daftar</a>
    </div>

    {{-- Notifikasi sukses / error --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm sm:text-base">
            {{ session('error') }}
        </div>
    @endif

    {{-- Total saldo --}}
    <div class="bg-white shadow-lg rounded-xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-700">Total Saldo</h3>
        <p class="text-2xl font-bold text-green-600 mt-2 break-words">
            Rp {{ number_format($totalSaldo, 0, ',', '.') }}
        </p>
    </div>

    {{-- Form debit --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-blue-700 mb-4">Ambil Saldo Tabungan</h3>
        <form method="POST" action="{{ route('pengurus.tabungan.debit.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            <input type="hidden" name="users_id" value="{{ $user->id }}">

            {{-- Input tanggal --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input 
                    type="date" 
                    name="tanggal" 
                    required
                    max="{{ date('Y-m-d') }}" 
                    pattern="\d{4}-\d{2}-\d{2}"
                    inputmode="numeric"
                    title="Format tanggal harus YYYY-MM-DD dan tidak boleh melebihi hari ini."
                    value="{{ old('tanggal') }}"
                    class="border border-gray-300 rounded-lg px-3 py-2 w-full 
                        focus:ring-2 focus:ring-blue-500 focus:outline-none 
                        @error('tanggal') border-red-500 @enderror">
                @error('tanggal')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Input nominal --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nominal Debit (Rp)</label>
                <input 
                    type="number" 
                    name="debit" 
                    required 
                    min="100"
                    max="{{ $totalSaldo }}"
                    value="{{ old('debit') }}"
                    class="border border-gray-300 rounded-lg px-3 py-2 w-full 
                        focus:ring-2 focus:ring-blue-500 focus:outline-none 
                        @error('debit') border-red-500 @enderror"
                    placeholder="Masukkan jumlah minimal 100">
                @error('debit')
                    <p class="text-sm text-red-600 mt-1">
                        @if ($message == 'Nominal harus diisi.')
                            Nominal harus diisi.
                        @elseif (str_contains($message, 'minimal'))
                            Nominal minimal 100.
                        @elseif (str_contains($message, 'Saldo tidak mencukupi'))
                            Saldo tidak mencukupi.
                        @else
                            {{ $message }}
                        @endif
                    </p>
                @enderror
            </div>

            <div class="flex items-end">
                <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg w-full md:w-auto transition duration-200">
                    Ambil Saldo
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Debit --}}
    <div class="bg-white shadow-lg rounded-xl p-4 sm:p-6 overflow-x-auto">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Riwayat Debit</h3>
        <table class="min-w-full text-left border border-gray-200 text-sm sm:text-base">
            <thead class="bg-red-100">
                <tr>
                    <th class="px-4 py-3 whitespace-nowrap">Tanggal</th>
                    <th class="px-4 py-3 whitespace-nowrap">Debit (Rp)</th>
                    <th class="px-4 py-3 whitespace-nowrap">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($debits as $debit)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($debit->tanggal)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-red-600 font-semibold whitespace-nowrap">
                            Rp {{ number_format($debit->debit, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-red-600 font-semibold">{{ ucfirst($debit->status) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-6 text-gray-500">Belum ada debit</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection