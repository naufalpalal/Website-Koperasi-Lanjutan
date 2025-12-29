@extends('pengurus.index')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow-sm border">

        {{-- HEADER --}}
        <div class="flex flex-wrap items-center justify-between mb-6 gap-3">
            <h2 class="text-lg font-semibold text-gray-700">
                Simpanan Sukarela
            </h2>

            {{-- ACTION BUTTON --}}
            <div class="flex items-center gap-2 text-sm">
                <a href="{{ route('pengurus.simpanan.sukarela.generate') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Generate Simpanan
                </a>

                <a href="{{ route('pengurus.simpanan.sukarela.pengajuan') }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Pengajuan Baru
                </a>

                <a href="{{ route('pengurus.simpanan.sukarela.riwayat') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                    Riwayat 
                </a>
            </div>
        </div>

        {{-- FILTER --}}
        <form method="GET" class="mb-5 flex flex-wrap items-end gap-4 text-sm">

            {{-- PERIODE --}}
            <div>
                <label class="block text-gray-600 mb-1">Periode</label>
                <select name="periode" class="border rounded-md px-3 py-2 text-sm w-40">
                    @foreach($periodeList as $p)
                        <option value="{{ $p }}" {{ request('periode') == $p ? 'selected' : '' }}>
                            {{ $p }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- SEARCH --}}
            <div>
                <label class="block text-gray-600 mb-1">Nama Anggota</label>
                <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari nama..."
                    class="border rounded-md px-3 py-2 text-sm w-56">
            </div>

            {{-- BUTTON --}}
            <div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Terapkan
                </button>
            </div>
        </form>

        {{-- TABEL --}}
        <div class="overflow-auto rounded-lg border">
            <table class="w-full text-sm border-collapse">

                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-4 py-2 border text-left font-normal">Nama</th>
                        <th class="px-4 py-2 border text-left font-normal">Bulan</th>
                        <th class="px-4 py-2 border text-left font-normal">Tahun</th>
                        <th class="px-4 py-2 border text-left font-normal">Nominal</th>
                        <th class="px-4 py-2 border text-left font-normal">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($simpanan as $item)
                        <tr class="hover:bg-gray-50 text-gray-700">
                            <td class="px-4 py-2 border">
                                {{ optional($item->user)->nama ?? '-' }}
                            </td>
                            <td class="px-4 py-2 border">{{ $item->bulan }}</td>
                            <td class="px-4 py-2 border">{{ $item->tahun }}</td>
                            <td class="px-4 py-2 border">
                                Rp {{ number_format($item->nilai, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2 border">
                                <span class="px-2 py-1 rounded text-xs
                                        @if ($item->status === 'Diajukan') bg-yellow-100 text-yellow-700
                                        @elseif ($item->status === 'Disetujui') bg-green-100 text-green-700
                                        @else bg-gray-100 text-gray-600 @endif">
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">
                                Data tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
@endsection