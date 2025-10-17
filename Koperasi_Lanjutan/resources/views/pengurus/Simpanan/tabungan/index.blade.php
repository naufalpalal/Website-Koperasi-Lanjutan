@extends('pengurus.index')

@section('title', 'Verifikasi Tabungan')

@section('content')
<div class="container mx-auto px-6 py-10">

    {{-- FORM TAMBAH TABUNGAN --}}
    <div class="bg-white shadow-lg rounded-xl p-6 max-w-lg mx-auto mb-10">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Tambah Tabungan Anggota</h2>

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

        {{-- FORM INPUT --}}
        <form action="{{ route('pengurus.tabungan.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- PILIH ANGGOTA --}}
            <div>
                <label for="users_id" class="block text-sm font-medium text-gray-700">Nama Anggota</label>
                <select name="users_id" id="users_id"class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm px-3 py-2" required>
                    <option value="">-- Pilih Anggota --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->nama }}</option>
                    @endforeach
                </select>
            </div>
            {{-- TANGGAL --}}
            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal"
                       value="{{ old('tanggal', date('Y-m-d')) }}"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm px-3 py-2" required>
            </div>

            {{-- NOMINAL --}}
            <div>
                <label for="nilai" class="block text-sm font-medium text-gray-700">Nominal Tabungan (Rp)</label>
                <input type="number" id="nilai" name="nilai" 
                    value="{{ old('nilai') }}" min="1000" step="1000"
                    placeholder="Masukkan nominal (contoh: 100000)"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm px-3 py-2" required>
            </div>

            {{-- TOMBOL SIMPAN --}}
            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- DAFTAR PENGAJUAN TABUNGAN --}}
    <div class="bg-white shadow-lg rounded-xl p-6">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Daftar Pengajuan Tabungan</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 rounded-lg">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Nama Anggota</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Nominal</th>
                        <th class="px-4 py-2">Bukti Transfer</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tabungans as $tabungan)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $tabungan->user?->nama ?? 'Tidak diketahui' }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($tabungan->tanggal)->format('d-m-Y') }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($tabungan->nilai, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-center">
                                @if ($tabungan->bukti_transfer)
                                    <div class="flex justify-center">
                                        <a href="{{ asset('uploads/bukti_transfer/' . $tabungan->bukti_transfer) }}" target="_blank">
                                            <img src="{{ asset('uploads/bukti_transfer/' . $tabungan->bukti_transfer) }}"
                                                alt="Bukti Transfer"
                                                class="w-16 h-16 object-cover rounded shadow hover:scale-105 transition-transform duration-200">
                                        </a>
                                    </div>
                                @else
                                    @if ($tabungan->status === 'diterima')
                                        <span class="text-green-600 font-semibold italic">Dibayar langsung</span>
                                    @else
                                        <span class="text-gray-400 italic">Belum ada</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-4 py-2 flex gap-2">
                                @if ($tabungan->status === 'pending')
                                    <form action="{{ route('pengurus.tabungan.approve', $tabungan->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                                            Setujui
                                        </button>
                                    </form>

                                    <form action="{{ route('pengurus.tabungan.reject', $tabungan->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                            Tolak
                                        </button>
                                    </form>
                                @else
                                    <span class="font-semibold text-gray-700">{{ ucfirst($tabungan->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">
                                Belum ada pengajuan tabungan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection