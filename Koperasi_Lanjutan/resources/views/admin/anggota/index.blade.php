@extends('admin.index')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Kelola Anggota</h5>
            <a href="{{ route('admin.anggota.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Anggota
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th>Tanggal Masuk</th>
                            <th width="180px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggota as $index => $a)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $a->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($a->tgl_lahir)->format('d-m-Y') }}</td>
                            <td>{{ ucfirst($a->jenis_kelamin) }}</td>
                            <td>{{ $a->alamat }}</td>
                            <td>{{ \Carbon\Carbon::parse($a->tgl_masuk)->format('d-m-Y') }}</td>
                            <td>
                                <a href="{{ route('admin.anggota.edit', $a->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.anggota.destroy', $a->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data anggota</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
