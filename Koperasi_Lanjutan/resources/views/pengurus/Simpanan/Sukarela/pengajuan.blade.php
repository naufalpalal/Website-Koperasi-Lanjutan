@extends('pengurus.index')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Daftar Pengajuan Perubahan Nominal Simpanan Sukarela</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">Nama Anggota</th>
                    <th class="px-4 py-2 border">Nominal Baru</th>
                    <th class="px-4 py-2 border">Bulan</th>
                    <th class="px-4 py-2 border">Tahun</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $item)
                <tr>
                    <td class="px-4 py-2 border">{{ $item->user->name }}</td>
                    <td class="px-4 py-2 border">Rp {{ number_format($item->nilai, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 border">{{ $item->bulan }}</td>
                    <td class="px-4 py-2 border">{{ $item->tahun }}</td>
                    <td class="px-4 py-2 border">{{ $item->status }}</td>
                    <td class="px-4 py-2 border">
                        <div class="flex space-x-2">
                            <form action="{{ route('admin.simpanan.sukarela.accPerubahan', [$item->id, 'Dibayar']) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                    ACC
                                </button>
                            </form>
                            <form action="{{ route('admin.simpanan.sukarela.accPerubahan', [$item->id, 'Ditolak']) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">Tidak ada pengajuan saat ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
