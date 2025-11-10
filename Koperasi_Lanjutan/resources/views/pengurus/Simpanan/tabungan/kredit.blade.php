@extends('pengurus.index')

@section('title', 'Kredit Tabungan')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 space-y-3 sm:space-y-0">
        <h2 class="text-2xl font-bold text-gray-800">
            Kredit Tabungan: {{ $user->nama }}
        </h2>
        <a href="{{ route('pengurus.tabungan.index') }}" 
           class="text-blue-600 hover:text-blue-800 text-sm font-medium">← Kembali ke Daftar</a>  
    </div>
    
    {{-- Total saldo --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-700">Total Saldo</h3>
        <p class="text-2xl font-bold text-green-600 mt-2">
            Rp {{ number_format($totalSaldo, 0, ',', '.') }}
        </p>
    </div>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded-xl mb-5">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Kredit Saldo --}}
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-8 shadow-md">
        <h3 class="text-lg font-semibold text-blue-700 mb-4">Tambah Saldo Tabungan</h3>
        <form method="POST" action="{{ route('pengurus.tabungan.kredit.store') }}" 
              class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                    class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none @error('tanggal') border-red-500 @enderror"
                >
                @error('tanggal')
                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            
            {{-- Input nominal --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                <input 
                    type="number" 
                    name="nilai" 
                    required 
                    min="100"
                    value="{{ old('nilai') }}"
                    class="border border-gray-300 rounded-lg px-3 py-2 w-full 
                        focus:ring-2 focus:ring-blue-500 focus:outline-none 
                        @error('nilai') border-red-500 @enderror"
                    placeholder="Masukkan minimal Rp 100">
                @error('nilai')
                    <p class="text-sm text-red-600 mt-1">
                        @if (str_contains($message, 'minimal'))
                            Nominal minimal Rp 100.
                        @else
                            {{ $message }}
                        @endif
                    </p>
                @enderror
            </div>
            
            {{-- Tombol simpan --}}
            <div class="flex items-end">
                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg font-semibold transition-all duration-200 shadow">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- Riwayat Tabungan --}}
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
        <h3 class="text-lg font-semibold text-gray-700 p-4 border-b">Riwayat Tabungan</h3>

        {{-- Scroll responsif --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-left border-t">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Nominal</th>
                        <th class="px-4 py-3">Bukti Transfer</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tabungans as $index => $item)
                        <tr class="border-b hover:bg-gray-100 transition">
                            <td class="px-4 py-3">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                            <td class="px-4 py-3 font-semibold text-green-600">
                                Rp {{ number_format($item->nilai, 0, ',', '.') }}
                            </td>

                            {{-- Bukti Transfer --}}
                            <td class="px-4 py-3 text-center">
                                @if ($item->bukti_transfer)
                                    <a href="{{ asset('uploads/bukti_transfer/' . $item->bukti_transfer) }}" target="_blank">
                                        <img src="{{ asset('uploads/bukti_transfer/' . $item->bukti_transfer) }}"
                                            alt="Bukti Transfer"
                                            class="w-16 h-16 object-cover rounded-lg shadow hover:scale-105 transition-transform duration-200 mx-auto">
                                    </a>
                                @else
                                    @if ($item->status === 'diterima')
                                        <span class="text-green-600 font-semibold italic">Dibayar langsung</span>
                                    @else
                                        <span class="text-gray-400 italic">Belum ada</span>
                                    @endif
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3 text-center">
                                @if($item->status === 'pending' && $item->bukti_transfer)
                                    <div class="flex justify-center gap-2 flex-wrap">
                                        <form action="{{ route('pengurus.tabungan.approve', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium">
                                                Terima
                                            </button>
                                        </form>
                                        <form action="{{ route('pengurus.tabungan.reject', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-medium">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-600 font-semibold capitalize">{{ $item->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">Belum ada tabungan masuk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection