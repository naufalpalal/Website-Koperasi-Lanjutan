@extends('pengurus.index')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white rounded-lg shadow-lg mt-8">
    <h3 class="text-2xl font-bold mb-6 text-gray-800">Kelola Simpanan Wajib</h3>

    {{-- Filter Bulan --}}
    <div class="mb-4 flex items-center justify-between">
        <form id="bulanForm" method="GET" action="" class="flex items-center gap-2">
            <label for="filterBulan" class="font-medium mr-2">Periode</label>
            <select id="filterBulan" name="bulan"
                    class="border rounded px-3 py-2 w-40 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    onchange="this.form.submit()">
                @if($bulan->isEmpty())
                    <option value="">{{ __('Belum ada data') }}</option>
                @else
                    @foreach($bulan as $b)
                        <option value="{{ $b }}" {{ request('bulan', now()->format('Y-m')) == $b ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($b.'-01')->translatedFormat('F Y') }}
                        </option>
                    @endforeach
                @endif
            </select>
        </form>

        {{-- Tombol Add & Edit Nominal --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('pengurus.simpanan.wajib_2.download') }}" 
            class="text-green-600 hover:text-green-700 transition" 
            title="Download Excel">
                <!-- Icon download -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M19.5 21a3 3 0 0 0 3-3V9a3 3 0 0 0-3-3h-5.379a.75.75 0 0 1-.53-.22L11.47 3.66A2.25 2.25 0 0 0 9.879 3H4.5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h15Zm-6.75-10.5a.75.75 0 0 0-1.5 0v4.19l-1.72-1.72a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l3-3a.75.75 0 1 0-1.06-1.06l-1.72 1.72V10.5Z" clip-rule="evenodd" />
                </svg>
            </a>

            <button id="masuk" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
                @if(!$master) data-nominal-empty="true" @endif>
                setting/add
            </button>
        </div>
  
    </div>

    {{-- Checkbox Select All --}}
  

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
                <tbody>
                    @forelse($anggota as $a)
                        @php
                            $simpanan = $simpananBulanIni->get($a->id);
                        @endphp
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $a->nama }}</td>
                            <td class="px-4 py-2">
                                {{ $simpanan ? 'Rp '.number_format($simpanan->nilai, 0, ',', '.') : '-' }}
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
                            <td colspan="5" class="text-center py-4">Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Aksi bawah --}}
        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('pengurus.simpanan.wajib_2.riwayat', $a->id ?? 0) }}"
               class="text-blue-600 hover:underline">
                Riwayat
            </a>

            <div class="flex gap-3">
            </div>
        </div>
    </form>
</div>
@endsection
