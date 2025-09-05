@extends('admin.index')

@section('title', 'Transaksi Simpanan Bulanan')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-semibold text-gray-700 mb-4">Transaksi Simpanan Bulanan</h1>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border">Anggota</th>
                        <th class="px-4 py-2 border">Wajib</th>
                        <th class="px-4 py-2 border">Sukarela</th>
                        <th class="px-4 py-2 border">Total</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $memberId => $simpanans)
                        @php
                            $member = $simpanans->first()->member;
                            $wajib = $simpanans->where('type', 'wajib')->first();
                            $sukarela = $simpanans->where('type', 'sukarela')->first();
                            $total = ($wajib->amount ?? 0) + ($sukarela->amount ?? 0);
                        @endphp
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $member->nama }}</td>
                            <td class="px-4 py-2">
                                Rp {{ number_format($wajib->amount ?? 0, 0, ',', '.') }}
                                <span class="ml-2 px-2 py-1 rounded text-xs
                                    {{ $wajib->status == 'success' ? 'bg-green-100 text-green-700' : ($wajib->status == 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $wajib->status ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                Rp {{ number_format($sukarela->amount ?? 0, 0, ',', '.') }}
                                <span class="ml-2 px-2 py-1 rounded text-xs
                                    {{ $sukarela && $sukarela->status == 'success' ? 'bg-green-100 text-green-700' : ($sukarela && $sukarela->status == 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $sukarela->status ?? '-' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 font-semibold">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.simpanan.edit', $wajib->id ?? $sukarela->id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                    Update
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Generate Button --}}
        <form action="{{ route('admin.simpanan.generate') }}" method="POST" class="mt-4">
            @csrf
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Generate Simpanan Bulan Ini
            </button>
        </form>

        {{-- Pagination (tidak ada, karena Collection biasa) --}}
        {{-- <div class="mt-4">
            {{ $transactions->links() }}
        </div> --}}
    </div>
</div>
@endsection
