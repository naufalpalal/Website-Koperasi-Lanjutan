@extends('Pengurus.index')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Daftar Pengajuan Pinjaman</h2>

    @foreach($pinjaman as $item)
        <div class="border-b py-3">
            <p><strong>{{ $item->user->nama }}</strong> - Rp{{ number_format($item->nominal, 0, ',', '.') }}</p>
            <a href="{{ route('pengurus.pinjaman.show', $item->id) }}" class="text-blue-600">Lihat Detail</a>
        </div>
    @endforeach
</div>
@endsection
