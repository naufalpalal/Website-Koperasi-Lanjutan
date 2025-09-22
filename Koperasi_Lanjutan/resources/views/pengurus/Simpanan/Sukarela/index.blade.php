@extends('pengurus.index')

@section('content')
    <div class="p-6 bg-white rounded shadow">
        <h2 class="text-xl font-bold mb-4">Persetujuan Simpanan Sukarela</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('pengurus.simpanan.sukarela.update') }}" method="POST">
            @csrf
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border">Pilih</th>
                        <th class="px-4 py-2 border">Nama Anggota</th>
                        <th class="px-4 py-2 border">Bulan</th>
                        <th class="px-4 py-2 border">Tahun</th>
                        <th class="px-4 py-2 border">Nominal</th>
                        <th class="px-4 py-2 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($simpanan as $item)
                        <tr>
                            <td class="px-4 py-2 border text-center">
                                <input type="checkbox" name="ids[]" value="{{ $item->id }}">
                            </td>
                            <td class="px-4 py-2 border">{{ $item->user->nama ?? '-' }}</td>
                            <td class="px-4 py-2 border">{{ $item->bulan }}</td>
                            <td class="px-4 py-2 border">{{ $item->tahun }}</td>
                            <td class="px-4 py-2 border">Rp {{ number_format($item->nilai, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border">{{ $item->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Simpan Persetujuan
                </button>
            </div>
        </form>
    </div>
@endsection
