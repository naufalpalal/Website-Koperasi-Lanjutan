@extends('user.index')

@section('title', 'Tabungan')

@section('content')
<div class="container mx-auto px-6 py-10">
    {{-- FORM TAMBAH TABUNGAN --}}
    <div class="bg-white shadow-lg rounded-xl p-6 max-w-lg mx-auto mb-10">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Tambah Tabungan</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.simpanan.tabungan.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
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

            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- DAFTAR TABUNGAN --}}
    <div class="bg-white shadow-lg rounded-xl p-6">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Daftar Tabungan</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
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
                @forelse ($tabungan as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $loop->iteration + ($tabungan->currentPage()-1) * $tabungan->perPage() }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td class="px-4 py-2">Rp {{ number_format($item->nilai, 0, ',', '.') }}</td>
                        <td class="px-4 py-2">
                            @if ($item->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    Pending
                                </span>
                            @elseif ($item->status == 'diterima')
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    diterima
                                </span>
                            @elseif ($item->status == 'ditolak')
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">
                                    ditolak
                                </span>
                            @endif
                        </td>

                            <!-- <form action="{{ route('user.simpanan.tabungan.destroy', $item->id) }}" method="POST" 
                                onsubmit="return confirm('Yakin hapus tabungan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                    Hapus
                                </button>
                            </form> -->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">Belum ada tabungan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div>
                <button type="submit" class="px-4 py-2 my-4 bg-green-600 text-white rounded hover:bg-green-700">
                    Penarikan Tabungan
                </button>
        </div>

        <div class="mt-4">
            {{ $tabungan->links() }}
        </div>
    </div>
</div>
@endsection
