@extends('Pengurus.index')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Daftar Pinjaman Anggota</h2>

        <!-- Tombol menuju halaman pengajuan pinjaman -->
        <a href="{{ route('pengurus.pinjaman.pengajuan') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
            Daftar Pengajuan
        </a>
    </div>

    <!-- Tabel daftar pinjaman -->
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Nama Anggota</th>
                    <th class="px-4 py-2 border">Nominal</th>
                    <th class="px-4 py-2 border">Bunga (%)</th>
                    <th class="px-4 py-2 border">Tenor (bulan)</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Tanggal</th>
                    <th class="px-4 py-2 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pinjaman as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border text-center">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 border">{{ $item->user->nama ?? '-' }}</td>
                        <td class="px-4 py-2 border">Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 border text-center">{{ $item->bunga ?? '-' }}</td>
                        <td class="px-4 py-2 border text-center">{{ $item->tenor ?? '-' }}</td>
                        <td class="px-4 py-2 border text-center">
                            @if($item->status == 'disetujui')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Disetujui</span>
                            @elseif($item->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">Menunggu</span>
                            @elseif($item->status == 'draft')
                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded">Draft</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">{{ ucfirst($item->status) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 border text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 border text-center">
                            <a href="{{ route('pengurus.angsuran.index', $item->id) }}" 
                               class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm px-3 py-1 rounded">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data pinjaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
