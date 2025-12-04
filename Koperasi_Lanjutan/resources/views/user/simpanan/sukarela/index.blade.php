@extends('user.index')

@section('title', 'Dashboard Simpanan Sukarela')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md">

    {{-- Judul --}}
    <h2 class="text-2xl font-bold mb-6">Simpanan Sukarela</h2>

    {{-- Ringkasan Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        {{-- Total Saldo --}}
        <div class="p-4 bg-blue-50 rounded border border-blue-200">
            <p class="text-sm text-gray-600 font-semibold">Total Saldo Terbayar</p>
            <p class="text-xl font-bold mt-1">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
        </div>

        {{-- Total Pengajuan Berhasil --}}
        <div class="p-4 bg-green-50 rounded border border-green-200">
            <p class="text-sm text-gray-600 font-semibold">Pengajuan Berhasil</p>
            <p class="text-xl font-bold mt-1">{{ $countBerhasil }} kali</p>
        </div>

        {{-- Total Pengajuan Gagal --}}
        <div class="p-4 bg-red-50 rounded border border-red-200">
            <p class="text-sm text-gray-600 font-semibold">Pengajuan Ditolak / Gagal</p>
            <p class="text-xl font-bold mt-1">{{ $countGagal }} kali</p>
        </div>

    </div>

    {{-- Status Simpanan Aktif --}}
    {{-- <div class="mb-6">
        <div class="p-4 rounded border 
            {{ Auth::user()->is_simpanan_aktif ? 'bg-green-50 border-green-300' : 'bg-red-50 border-red-300' }}">
            
            <p class="text-sm font-semibold">
                Status Simpanan:
            </p>

            @if(Auth::user()->is_simpanan_aktif)
                <p class="text-lg font-bold text-green-700">Aktif</p>
            @else
                <p class="text-lg font-bold text-red-700">Tidak Aktif</p>
            @endif
        </div>
    </div> --}}

    {{-- Status Bulan Ini --}}
    <div class="mb-6 p-4 bg-gray-50 border rounded">
        <h3 class="text-xl font-semibold mb-3">
            Status Bulan Ini ({{ now()->format('m/Y') }})
        </h3>

        @if($bulanIni)
            <p>Status: 
                <span class="font-bold 
                    {{ $bulanIni->status == 'berhasil' ? 'text-green-600' : 'text-yellow-600' }}">
                    {{ ucfirst($bulanIni->status) }}
                </span>
            </p>
            <p>Nominal: <strong>Rp {{ number_format($bulanIni->nilai, 0, ',', '.') }}</strong></p>
            <p>Terakhir update: {{ $bulanIni->updated_at->format('d M Y') }}</p>
        @else
            <p class="text-gray-600">Belum ada catatan untuk bulan ini.</p>
        @endif
    </div>

    {{-- Tombol Aksi --}}
    <div class="flex items-center gap-3">

        <a href="{{ route('user.simpanan.sukarela.riwayat') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            Lihat Riwayat
        </a>

        <a href="{{ route('user.simpanan.sukarela.pengajuan') }}"
           class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
            Ajukan Perubahan Nominal
        </a>

        {{-- Toggle Simpanan --}}
        {{-- @if (Auth::user()->is_simpanan_aktif)
            <a href="{{ route('user.simpanan.sukarela.libur') }}"
               class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                Nonaktifkan Simpanan
            </a>
        @else
            <form action="{{ route('simpanan.sukarela.toggle') }}" method="POST">
                @csrf
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                    Aktifkan Simpanan
                </button>
            </form>
        @endif --}}

    </div>

</div>
@endsection
