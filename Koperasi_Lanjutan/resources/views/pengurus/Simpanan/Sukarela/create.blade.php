@extends('admin.index')

@section('content')
<div class="container">
    <h3>Tambah Setoran Sukarela</h3>

    <form action="{{ route('simpanan_sukarela.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Anggota</label>
            <select name="member_id" class="form-control" required>
                <option value="">-- Pilih Anggota --</option>
                @foreach($anggota as $a)
                    <option value="{{ $a->id }}">{{ $a->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Jumlah</label>
            <input type="number" name="amount" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Periode</label>
            <input type="month" name="periode" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('simpanan_sukarela.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
