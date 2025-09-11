@extends('admin.index')

@section('title', 'Persetujuan Simpanan Sukarela')
@extends('admin.layouts.navbar')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md mt-24 p-6">
        <h2 class="text-xl font-semibold mb-4">Daftar Pengajuan Perubahan Simpanan Sukarela</h2>

        @if (session('success'))
            <div class="p-3 mb-4 text-green-700 bg-green-100 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">Anggota</th>
                    <th class="border p-2">Bulan</th>
                    <th class="border p-2">Jumlah</th>
                    <th class="border p-2">Catatan</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pending as $item)
                    <tr>
                        <td class="border p-2">{{ $item->member->nama }}</td>
                        <td class="border p-2">{{ \Carbon\Carbon::parse($item->month)->format('F Y') }}</td>
                        <td class="border p-2">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                        <td class="border p-2">{{ $item->note ?? '-' }}</td>
                        <td class="border p-2">
                            <form action="{{ route('admin.simpanan.sukarela.process', $item->id) }}" method="POST"
                                class="inline">
                                @csrf
                                <input type="hidden" name="status" value="success">
                                <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                    Setujui
                                </button>
                            </form>
                            <form action="{{ route('admin.simpanan.sukarela.process', $item->id) }}" method="POST"
                                class="inline">
                                @csrf
                                <input type="hidden" name="status" value="failed">
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                    Tolak
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
