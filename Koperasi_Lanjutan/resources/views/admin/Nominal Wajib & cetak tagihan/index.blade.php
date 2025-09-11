@extends('admin.layouts.navbar')

@section('title', 'Nominal Wajib & Cetak Tagihan')

@section('content')
<div class="container mt-4">
    <h2>Nominal Wajib & Cetak Tagihan</h2>
    <div class="card mt-3">
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="nominal" class="form-label">Nominal Wajib</label>
                    <input type="number" class="form-control" id="nominal" name="nominal" placeholder="Masukkan nominal wajib">
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-success">Cetak Tagihan</button>
            </form>
        </div>
    </div>
</div>
@endsection
