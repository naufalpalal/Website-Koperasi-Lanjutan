@extends('pengurus.index')

@section('content')
<div class="container">
    <h2>Riwayat Simpanan Sukarela</h2>

    {{-- Form Filter --}}
    <form action="{{ route('pengurus.simpanan.sukarela.riwayat') }}" method="GET" class="row g-3 mt-3">
        {{-- Filter Anggota (ID) --}}
        <div class="col-md-3">
            <label for="id" class="form-label">ID Anggota (Opsional)</label>
            <input type="text" name="id" id="id" class="form-control" value="{{ $id }}">
        </div>

        {{-- Filter Bulan --}}
        <div class="col-md-3">
            <label for="bulan" class="form-label">Bulan</label>
            <select name="bulan" id="bulan" class="form-select">
                <option value="">-- Semua Bulan --</option>
                @foreach (range(1, 12) as $b)
                    <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filter Tahun --}}
        <div class="col-md-3">
            <label for="tahun" class="form-label">Tahun</label>
            <select name="tahun" id="tahun" class="form-select">
                <option value="">-- Semua Tahun --</option>
                @foreach (range(date('Y'), date('Y') - 5) as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tombol --}}
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Filter</button>
            <a href="{{ route('pengurus.simpanan.sukarela.riwayat') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    {{-- Informasi Anggota --}}
    @if($anggota)
        <div class="alert alert-info mt-3">
            <strong>Anggota:</strong> {{ $anggota->name }} (ID: {{ $anggota->id }})
        </div>
    @endif

    {{-- Tabel Riwayat --}}
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Anggota</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Tanggal Simpan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $index => $item)
                    <tr>
                        <td>{{ $index + $riwayat->firstItem() }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }}</td>
                        <td>{{ $item->tahun }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data riwayat simpanan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    {{ $riwayat->links() }}
</div>
@endsection
