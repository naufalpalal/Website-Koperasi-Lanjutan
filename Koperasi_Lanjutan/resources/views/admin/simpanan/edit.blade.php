@extends('admin.index')

@section('content')
<div class="container">
    <h1>Update Transaksi Simpanan</h1>

    <form action="{{ route('admin.simpanan.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="success" {{ $transaction->status == 'success' ? 'selected' : '' }}>Success</option>
                <option value="failed" {{ $transaction->status == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Catatan / Alasan (jika gagal)</label>
            <textarea name="note" class="form-control">{{ $transaction->note }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="{{ route('admin.simpanan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
