@extends('admin.index')

@section('content')
    <div class="container">
        <h1 class="mb-4">Update Status Simpanan</h1>

        <div class="card shadow-sm p-4">
            <form action="{{ route('admin.simpanan.update', $transaction->id) }}" method="POST">
                @csrf
                @method('PUT')
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($simpanans as $simpanan)
                            <tr>
                                <td>{{ ucfirst($simpanan->type) }}</td>
                                <td>
                                    <select name="status[{{ $simpanan->id }}]" class="form-select">
                                        <option value="pending" {{ $simpanan->status == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="success" {{ $simpanan->status == 'success' ? 'selected' : '' }}>
                                            Success</option>
                                        <option value="failed" {{ $simpanan->status == 'failed' ? 'selected' : '' }}>Failed
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <textarea name="note[{{ $simpanan->id }}]" class="form-control" rows="2">{{ $simpanan->note }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('admin.simpanan.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
