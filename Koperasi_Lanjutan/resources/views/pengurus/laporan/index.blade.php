@extends('admin.index')

@section('content')
<div class="container">
    <h3>Kelola Laporan</h3>
    {{-- <a href="{{ route('laporan.create') }}" class="btn btn-primary mb-3">Tambah Laporan</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $l)
            <tr>
                <td>{{ $l->judul }}</td>
                <td>{{ $l->tanggal }}</td>
                <td>
                    <a href="{{ route('laporan.edit', $l->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('laporan.destroy', $l->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table> --}}
</div>
@endsection
