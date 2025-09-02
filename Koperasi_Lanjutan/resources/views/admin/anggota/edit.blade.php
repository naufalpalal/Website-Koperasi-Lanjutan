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
                    <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir"
                        value="{{ old('tgl_lahir', $anggota->tgl_lahir) }}" required>
                </div>

                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $anggota->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $anggota->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $anggota->alamat) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                    <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk"
                        value="{{ old('tgl_masuk', $anggota->tgl_masuk) }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.anggota.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
