<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
@extends('admin.index')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Kelola Anggota</h5>
            <a href="{{ route('admin.anggota.create') }}" class="btn btn-warning">
                <i class="bi bi-plus-circle"></i>
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($anggota->count())
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($anggota as $index => $a)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:48px;height:48px;font-size:1.5rem;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-0">{{ $a->nama }}</h5>
                                    <small class="text-muted">{{ $a->nip ?? '-' }}</small>
                                </div>
                            </div>
                            <ul class="list-unstyled mb-3">
                                <li><i class="bi bi-telephone"></i> {{ $a->no_telepon }}</li>
                                <li><i class="bi bi-geo-alt"></i> {{ $a->alamat_rumah ?? '-' }}</li>
                                <li><i class="bi bi-calendar"></i>
                                    @if($a->tanggal_lahir)
                                        {{ $a->tempat_lahir ?? '-' }}, {{ \Carbon\Carbon::parse($a->tanggal_lahir)->format('d-m-Y') }}
                                    @else
                                        {{ $a->tempat_lahir ?? '-' }}
                                    @endif
                                </li>
                            </ul>
                            <div>
                                <a href="{{ route('admin.anggota.edit', $a->id) }}" class="btn btn-success btn-sm me-2">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.anggota.destroy', $a->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center text-muted py-5">Belum ada data anggota</div>
            @endif
        </div>
    </div>
</div>
@endsection
</body>
</html>
