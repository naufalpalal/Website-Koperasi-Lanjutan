@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Daftar Permintaan Reset Password</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIP</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $req)
                <tr>
                    <td>{{ $req->nama }}</td>
                    <td>{{ $req->nip }}</td>
                    <td>
                        @if($req->status === 'pending')
                            <span class="badge bg-warning">Menunggu</span>
                        @elseif($req->status === 'approved')
                            <span class="badge bg-success">Disetujui</span>
                        @else
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if($req->status === 'pending')
                            <form action="{{ route('pengurus.password.reset.approve', $req->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>

                            <form action="{{ route('pengurus.password.reset.reject', $req->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                            </form>
                        @else
                            <em>-</em>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada permintaan reset password</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
