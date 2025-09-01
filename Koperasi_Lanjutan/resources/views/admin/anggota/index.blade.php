@extends('admin.index')

@section('content')
<div class="container">
    <h3>Kelola Anggota</h3>
    {{-- <a href="{{ route('anggota.create') }}" class="btn btn-primary mb-3">Tambah Anggota</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($anggota as $a)
            <tr>
                <td>{{ $a->name }}</td>
                <td>{{ $a->email }}</td>
                <td>
                    <a href="{{ route('anggota.edit', $a->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('anggota.destroy', $a->id) }}" method="POST" style="display:inline-block;">
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
