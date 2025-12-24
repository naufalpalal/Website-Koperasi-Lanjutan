@extends('user.index')

@section('title', 'Daftar Angsuran')

@section('content')
    <div class="p-6 bg-white rounded-xl shadow-lg">

        <h2 class="text-2xl font-bold mb-4">
            📄 Daftar Angsuran Pinjaman
        </h2>

        {{-- Info Pinjaman --}}
        <div class="mb-5 p-4 bg-green-50 rounded-lg border">
            <p>
                ✅ <strong>Status:</strong>
                <span class="text-green-700 font-semibold">
                    Pinjaman telah disetujui
                </span>
            </p>
            <p><strong>Nominal:</strong> Rp {{ number_format($pinjaman->nominal, 0, ',', '.') }}</p>
            <p><strong>Tenor:</strong> {{ $pinjaman->tenor }} bulan</p>
            <p><strong>Angsuran / Bulan:</strong> Rp {{ number_format($pinjaman->angsuran_bulanan, 0, ',', '.') }}</p>
        </div>

        {{-- Tabel Angsuran --}}
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-100 text-center">
                        <th class="border p-2">Bulan</th>
                        <th class="border p-2">Tanggal</th>
                        <th class="border p-2">Jumlah</th>
                        <th class="border p-2">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($angsuran as $a)
                        <tr class="text-center">
                            <td class="p-2 border">{{ $a->bulan_ke }}</td>
                            <td class="p-2 border">
                                {{ \Carbon\Carbon::parse($a->tanggal_bayar)->format('d M Y') }}
                            </td>
                            <td class="p-2 border">
                                Rp {{ number_format($a->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            <td class="p-2 border">
                                @if($a->status === 'lunas')
                                    <span class="text-green-600 font-semibold">Lunas</span>
                                @else
                                    <span class="text-yellow-600 font-semibold">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500">
                                Angsuran belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>
@endsection