@extends('pengurus.index')

@section('title', 'Persetujuan Simpanan Sukarela')

@section('content')
    <div class="container mx-auto pt-12 px-10">
        <div class="bg-white rounded-xl shadow p-6">
            {{-- Judul + Link Kembali --}}
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-700">Persetujuan Simpanan Sukarela</h1>
                <a href="{{ route('pengurus.simpanan.sukarela.index') }}"
                    class="text-blue-600 hover:underline flex items-center">
                    ← Kembali
                </a>
            </div>

            {{-- Pesan sukses --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif
            <form action="{{ route('pengurus.simpanan.sukarela.pengajuan') }}" method="GET">
                @csrf
                <table class="w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2">Anggota</th>
                            <th class="border p-2">Nominal</th>
                            <th class="border p-2">Periode</th>
                            <th class="border p-2">Status</th>
                            <th class="border p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuan as $item)
                            <tr>
                                <td class="border p-2">{{ $item->user->nama }}</td>
                                <td class="border p-2">Rp {{ number_format($item->nilai, 0, ',', '.') }}</td>
                                <td class="border p-2">{{ $item->bulan }} - {{ $item->tahun }}</td>
                                <td class="border p-2">
                                    @if ($item->status == 'Pending')
                                        <span class="text-yellow-600 font-semibold">Pending</span>
                                    @elseif($item->status == 'Disetujui')
                                        <span class="text-green-600 font-semibold">Disetujui</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Ditolak</span>
                                    @endif
                                </td>
                                <td class="border p-2 text-center">
                                    @if ($item->status == 'Pending')
                                        <form method="POST"
                                            action="{{ route('pengurus.simpanan.sukarela.approve', $item->id) }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                                Setujui
                                            </button>
                                        </form>
                                        <form method="POST"
                                            action="{{ route('pengurus.simpanan.sukarela.reject', $item->id) }}"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                                Tolak
                                            </button>
                                        </form>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border p-2 text-center text-gray-500">
                                    Tidak ada pengajuan simpanan sukarela.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>
    </div>
@endsection
