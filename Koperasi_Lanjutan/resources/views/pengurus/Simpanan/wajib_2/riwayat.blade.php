@extends('pengurus.index')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg mt-8">
    <h3 class="text-2xl font-bold mb-6 text-gray-800">
        Riwayat Simpanan Wajib - {{ $anggota->nama }}
    </h3>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded-lg shadow">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Periode</th>
                    <th class="px-4 py-2 text-left">Nominal</th>
                    <th class="px-4 py-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $r)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2">
                        {{ \Carbon\Carbon::createFromDate($r->tahun, $r->bulan, 1)->translatedFormat('F Y') }}
                    </td>
                    <td class="px-4 py-2">Rp {{ number_format($r->nilai, 0, ',', '.') }}</td>
                    <td class="px-4 py-2">
                        <span class="{{ $r->status === 'Dibayar' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $r->status ?? '-' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-4">Belum ada riwayat simpanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <a href="{{ url()->previous() }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            Kembali
        </a>
    </div>
</div>
@endsection
