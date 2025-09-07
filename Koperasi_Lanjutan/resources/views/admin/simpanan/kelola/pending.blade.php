@extends('admin.index')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-md">
    <h2 class="text-xl font-semibold mb-4">Pengajuan Simpanan Sukarela Pending</h2>

    @if($pending->isEmpty())
        <p class="text-gray-600">Tidak ada pengajuan pending.</p>
    @else
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Nama Anggota</th>
                    <th class="border p-2">Bulan</th>
                    <th class="border p-2">Jumlah</th>
                    <th class="border p-2">Catatan</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pending as $item)
                    <tr>
                        <td class="border p-2">{{ $item->member->nama }}</td>
                        <td class="border p-2">{{ \Carbon\Carbon::parse($item->month)->translatedFormat('F Y') }}</td>
                        <td class="border p-2">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                        <td class="border p-2">{{ $item->note ?? '-' }}</td>
                        <td class="border p-2 flex space-x-2">
                            <form action="{{ route('admin.simpanan.sukarela.process', $item->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="success">
                                <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    Setujui
                                </button>
                            </form>
                            <form action="{{ route('admin.simpanan.sukarela.process', $item->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="failed">
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    Tolak
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
