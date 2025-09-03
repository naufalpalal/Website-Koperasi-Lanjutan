@extends('admin.index')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Edit Anggota</h5>
            <a href="{{ route('admin.anggota.index') }}" class="btn btn-light btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.anggota.update', $anggota->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama"
                        value="{{ old('nama', $anggota->nama) }}" required>
                </div>

                <div class="mb-3">
                    <label for="no_telepon" class="form-label">No Telepon</label>
                    <input type="text" class="form-control" id="no_telepon" name="no_telepon"
                        value="{{ old('no_telepon', $anggota->no_telepon) }}" required>
                </div>

                <div class="mb-3">
                    <label for="nip" class="form-label">NIP</label>
                    <input type="text" class="form-control" id="nip" name="nip"
                        value="{{ old('nip', $anggota->nip) }}">
                </div>

                <div class="mb-3">
                    <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                        value="{{ old('tempat_lahir', $anggota->tempat_lahir) }}">
                </div>

                <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                        value="{{ old('tanggal_lahir', $anggota->tanggal_lahir) }}">
                </div>

                <div class="mb-3">
                    <label for="alamat_rumah" class="form-label">Alamat Rumah</label>
                    <textarea class="form-control" id="alamat_rumah" name="alamat_rumah" rows="3">{{ old('alamat_rumah', $anggota->alamat_rumah) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
