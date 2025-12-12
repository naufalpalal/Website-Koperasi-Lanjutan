@extends('pengurus.index')

@section('content')
    <div class="max-w-5xl mx-auto p-4 sm:p-6 bg-white rounded-lg shadow-lg mt-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <h3 class="text-lg font-semibold text-gray-800 mr-3">Kelola Simpanan Wajib</h3>
                <form id="bulanForm" method="GET" action="" class="flex items-center gap-2">
                    <label for="filterBulan" class="text-sm text-gray-600">Periode</label>
                    <select id="filterBulan" name="bulan"
                        class="border rounded px-2 py-1 text-sm w-40 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        onchange="this.form.submit()">
                        @if($bulan->isEmpty())
                            <option value="">{{ __('Belum ada data') }}</option>
                        @else
                            @foreach($bulan as $b)
                                <option value="{{ $b }}" {{ request('bulan', now()->format('Y-m')) == $b ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($b . '-01')->translatedFormat('F Y') }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </form>
            </div>

            <div class="flex flex-wrap items-center gap-2 justify-start sm:justify-end">
                <form action="{{ route('pengurus.simpanan.wajib_2.download') }}" method="GET" class="inline-flex items-center">
                    <input type="hidden" name="bulan" value="{{ $periodeFilter }}">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-600 text-white rounded-md text-sm hover:bg-green-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                            <path fill-rule="evenodd"
                                d="M19.5 21a3 3 0 0 0 3-3V9a3 3 0 0 0-3-3h-5.379a.75.75 0 0 1-.53-.22L11.47 3.66A2.25 2.25 0 0 0 9.879 3H4.5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h15Zm-6.75-10.5a.75.75 0 0 0-1.5 0v4.19l-1.72-1.72a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l3-3a.75.75 0 1 0-1.06-1.06l-1.72 1.72V10.5Z"
                                clip-rule="evenodd" />
                        </svg>
                        Tagihan
                    </button>
                </form>

                <form action="{{ route('pengurus.simpanan.wajib_2.download_tahunan') }}" method="GET" class="inline-flex items-center">
                    <select name="periode" class="border text-sm px-2 py-1 rounded-md">
                        <option value="3">3B</option>
                        <option value="6">6B</option>
                        <option value="12" selected>12B</option>
                    </select>
                    <button type="submit" class="ml-1 px-3 py-1.5 bg-yellow-600 text-white rounded-md text-sm hover:bg-yellow-700 transition">
                        Laporan
                    </button>
                </form>

                <button id="masuk" class="ml-1 bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm hover:bg-blue-700 transition"
                    @if(!$master) data-nominal-empty="true" @endif>
                    Atur
                </button>
            </div>
        </div>

        {{-- Search --}}
        <div class="mb-4">
            <div class="flex items-center gap-2 w-full md:w-96">
                <input id="searchInput" type="text" placeholder="Cari nama anggota..."
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                <button id="btnSearch" type="button"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Cari
                </button>
            </div>
        </div>

        {{-- Daftar anggota dan simpanan --}}
        <form action="{{ route('pengurus.simpanan.wajib_2.updateStatus') }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border rounded-lg shadow">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-left">
                            <th class="px-4 py-2">Nama Anggota</th>
                            <th class="px-4 py-2">Nominal</th>
                            <th class="px-4 py-2">Bulan</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody id="anggotaTableBody">
                        @forelse($anggota as $a)
                            @php
                                $simpanan = $simpananBulanIni->get($a->id);
                            @endphp
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $a->nama }}</td>
                                <td class="px-4 py-2">
                                    {{ $simpanan ? 'Rp ' . number_format($simpanan->nilai, 0, ',', '.') : '-' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $simpanan ? \Carbon\Carbon::createFromDate($simpanan->tahun, $simpanan->bulan, 1)->translatedFormat('F Y') : '-' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $simpanan ? $simpanan->status : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination controls (client-side) --}}
            <div class="mt-4 flex justify-center" id="paginationContainer"></div>

            {{-- Aksi bawah --}}
            <div class="flex items-center justify-between mt-6">
                <div class="flex gap-4">
                    <a href="{{ route('pengurus.simpanan.wajib_2.riwayat', $a->id ?? 0) }}"
                        class="text-blue-600 hover:underline">
                        Riwayat
                    </a>
                    <a href="{{ route('pengurus.simpanan.wajib_2.lihat_bukti', $a->id) }}"
                        class="text-blue-600 hover:underline">
                        Lihat bukti pembayaran
                    </a>
                </div>
            </div>
    </div>
    </form>
    </div>
@endsection