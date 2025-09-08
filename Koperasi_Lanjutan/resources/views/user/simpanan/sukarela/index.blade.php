@extends('user.index')

@section('title', 'Simpanan Sukarela')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-md">
    <h2 class="text-xl font-semibold mb-4">Daftar Simpanan Sukarela</h2>

    @if(session('success'))
        <div class="p-3 mb-4 text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border-collapse border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">Bulan</th>
                <th class="border p-2">Jumlah</th>
                <th class="border p-2">Status</th>
                <th class="border p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sukarela as $item)
                <tr>
                    <td class="border p-2">{{ \Carbon\Carbon::parse($item->month)->format('F Y') }}</td>
                    <td class="border p-2">Rp {{ number_format($item->amount,0,',','.') }}</td>
                    <td class="border p-2">
                        <span class="px-2 py-1 rounded-lg text-white 
                            {{ $item->status == 'pending' ? 'bg-yellow-500' : ($item->status == 'success' ? 'bg-green-500' : 'bg-red-500') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="border p-2 text-center">
                        @if($item->status != 'pending')
                        <form action="{{ route('simpanan.sukarela.update', $item->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="number" name="amount" placeholder="Nominal baru" class="p-1 border rounded" required>
                            <input type="text" name="note" placeholder="Catatan" class="p-1 border rounded">
                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Ajukan Perubahan
                            </button>
                        </form>
                        @else
                            <em>Sedang menunggu persetujuan</em>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
