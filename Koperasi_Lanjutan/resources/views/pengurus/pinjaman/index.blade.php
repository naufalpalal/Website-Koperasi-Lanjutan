@extends('admin.index')

@section('title', 'Transaksi Pinjaman Koperasi')

@section('content')
    <div class="container mx-auto pt-12 px-10">
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-2xl font-semibold text-gray-700 mb-6">Transaksi Pinjaman Koperasi</h1>

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tabel Pinjaman --}}
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full text-sm text-left border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">Anggota</th>
                            <th class="px-4 py-2 border">Tanggal Pengajuan</th>
                            <th class="px-4 py-2 border">Jumlah Pinjaman</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pinjamans as $pinjaman)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $pinjaman->member->nama }}</td>
                                <td class="px-4 py-2">{{ $pinjaman->created_at->format('d-m-Y') }}</td>
                                <td class="px-4 py-2 font-medium">
                                    Rp {{ number_format($pinjaman->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2">
                                    <span
                                        class="px-2 py-1 rounded text-xs
                                        {{ $pinjaman->status == 'diterima' ? 'bg-green-100 text-green-700' :
                                           ($pinjaman->status == 'ditolak' ? 'bg-red-100 text-red-700' :
                                           'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($pinjaman->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 flex gap-2">
                                    {{-- Tombol Terima --}}
                                    <form action="{{ route('admin.pinjaman.update', $pinjaman->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="diterima">
                                        <button type="submit"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm shadow">
                                            Terima
                                        </button>
                                    </form>

                                    {{-- Tombol Tolak --}}
                                    <form action="{{ route('admin.pinjaman.update', $pinjaman->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="ditolak">
                                        <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm shadow">
                                            Tolak
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">
                                    Tidak ada data pinjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection