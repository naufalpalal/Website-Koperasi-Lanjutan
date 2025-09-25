@extends('user.index')

@section('content')
    <div class="p-6 bg-white rounded shadow">
        <h2 class="text-xl font-bold mb-4">Simpanan Sukarela</h2>

        <div class="mb-4">
            <p><strong>Total Saldo Dibayar:</strong> Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
        </div>

        <div class="mb-4">
            <h3 class="font-semibold">Status Bulan Ini ({{ now()->month }}/{{ now()->year }})</h3>
            @if($bulanIni)
                <p>Status: {{ $bulanIni->status }}</p>
                <p>Nominal: Rp {{ number_format($bulanIni->nilai, 0, ',', '.') }}</p>
            @else
                <p>Belum ada catatan untuk bulan ini.</p>
            @endif
        </div>

        <a href="{{ route('user.simpanan.sukarela.riwayat') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Lihat Riwayat
        </a>

        <a href="{{ route('user.simpanan.sukarela.pengajuan') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Ajukan Simpanan Sukarela
        </a>
    </div>
@endsection