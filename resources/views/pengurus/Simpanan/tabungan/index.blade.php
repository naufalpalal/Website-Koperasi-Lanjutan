@extends('pengurus.index')

@section('title', 'Verifikasi Tabungan')

@section('content')
<div class="container mx-auto px-6 py-10">
    <div class="bg-white shadow-lg rounded-xl p-6">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Daftar Pengajuan Tabungan</h2>

        {{-- Notifikasi --}}
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
                        <th class="px-4 py-2 text-left">No</th>
                        <th class="px-4 py-2 text-left">Nama Anggota</th>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Nominal</th>
                        <th class="px-4 py-2 text-left">Status / Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tabungans as $tabungan)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $tabungan->user?->nama ?? 'Tidak diketahui' }}</td>
                            <td>{{ \Carbon\Carbon::parse($tabungan->tanggal)->format('d-m-Y') }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($tabungan->nilai, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 flex gap-2">
                                @if($tabungan->status === 'pending')
    <form action="{{ route('pengurus.tabungan.approve', $tabungan->id) }}" method="POST">
        @csrf
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
            Setujui
        </button>
    </form>
    <form action="{{ route('pengurus.tabungan.reject', $tabungan->id) }}" method="POST">
        @csrf
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
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
                            <td colspan="5" class="text-center py-4 text-gray-500">Belum ada pengajuan tabungan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
