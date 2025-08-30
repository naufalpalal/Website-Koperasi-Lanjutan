@extends('admin.dashboard')

@section('content')
<div class="container mt-4">
    <h2>Daftar Simpanan Sukarela</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Jumlah Simpanan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Budi Santoso</td>
                <td>Rp 500.000</td>
                <td>2024-06-01</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Siti Aminah</td>
                <td>Rp 300.000</td>
                <td>2024-06-02</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
