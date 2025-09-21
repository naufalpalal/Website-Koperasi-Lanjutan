@extends('pengurus.index')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Dashboard Simpanan Sukarela</h2>

    {{-- Tombol navigasi ke pengajuan --}}
    <div class="mb-4">
        <a href="{{ route('pengurus.simpanan.sukarela.pengajuan') }}" 
           class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Lihat Pengajuan Perubahan Nominal
        </a>
    </div>

    {{-- Konten dashboard utama, misalnya daftar simpanan --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">Nama Anggota</th>
                    <th class="px-4 py-2 border">Nominal</th>
                    <th class="px-4 py-2 border">Bulan</th>
                    <th class="px-4 py-2 border">Tahun</th>
                    <th class="px-4 py-2 border">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($simpanan as $item)
                <tr>
                    <td class="px-4 py-2 border">{{ $item->user->name }}</td>
                    <td class="px-4 py-2 border">Rp {{ number_format($item->nilai, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 border">{{ $item->bulan }}</td>
                    <td class="px-4 py-2 border">{{ $item->tahun }}</td>
                    <td class="px-4 py-2 border">{{ $item->status }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Tidak ada data simpanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
