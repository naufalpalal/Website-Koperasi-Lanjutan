@extends('user.index')

@section('content')
<div class="p-6 bg-white rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Simpanan Sukarela</h2>

    <p class="mb-2">Total Saldo: 
        <span class="font-semibold">Rp{{ number_format($totalSaldo, 0, ',', '.') }}</span>
    </p>

    @if($bulanIni)
        <p>Status bulan ini: 
            <span class="px-2 py-1 rounded 
                @if($bulanIni->status == 'Dibayar') bg-green-200 text-green-800 
                @elseif($bulanIni->status == 'Diajukan') bg-yellow-200 text-yellow-800 
                @elseif($bulanIni->status == 'Libur') bg-red-200 text-red-800 
                @else bg-gray-200 text-gray-800 @endif">
                {{ $bulanIni->status }}
            </span>
        </p>
    @else
        <p class="text-gray-500">Belum ada data untuk bulan ini.</p>
    @endif

    {{-- Form Ajukan Libur --}}
    <form action="{{ route('user.simpanan.sukarela.ajukanLibur') }}" method="POST" class="mt-4">
        @csrf
        <label class="block mb-2">Ajukan Libur Potongan:</label>
        <textarea name="alasan" class="w-full border rounded p-2 mb-2" placeholder="Tuliskan alasan Anda"></textarea>
        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
            Ajukan
        </button>
    </form>

    {{-- Tombol ke Halaman Pengajuan Perubahan Nominal --}}
    <div class="mt-4">
        <a href="{{ route('user.simpanan.sukarela.pengajuan') }}" 
           class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Ajukan Perubahan Nominal
        </a>
    </div>

    <a href="{{ route('user.simpanan.sukarela.riwayat') }}" 
       class="mt-4 inline-block text-blue-600 hover:underline">
        Lihat Riwayat
    </a>
</div>
@endsection
