@extends('pengurus.index')


@section('content')
<div class="container">
    <h3>Daftar Simpanan Sukarela</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('pengurus.simpanan.sukarela.index') }}" class="btn btn-primary mb-3">Tambah Setoran</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Anggota</th>
                <th>Periode</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Alasan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($simpanan as $item)
            <tr>
                <td>{{ $item->member->name }}</td>
                <td>{{ \Carbon\Carbon::parse($item->periode)->format('F Y') }}</td>
                <td>Rp{{ number_format($item->amount,0,',','.') }}</td>
                <td>
                    <span class="badge bg-info">{{ ucfirst($item->status) }}</span>
                </td>
                <td>{{ $item->alasan ?? '-' }}</td>
                <td>
                    @if($item->status === 'diajukan')
                        <form action="{{ route('simpanan_sukarela.acc', [$item->id, 'libur']) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button class="btn btn-warning btn-sm">Setujui Libur</button>
                        </form>
                        <form action="{{ route('simpanan_sukarela.acc', [$item->id, 'dibayar']) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button class="btn btn-success btn-sm">Tolak (Tetap Bayar)</button>
                        </form>
                    @elseif($item->status === 'dibayar')
                        <form action="{{ route('simpanan_sukarela.acc', [$item->id, 'ditarik']) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button class="btn btn-danger btn-sm">Tarik</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $simpanan->links() }}
</div>
@endsection
