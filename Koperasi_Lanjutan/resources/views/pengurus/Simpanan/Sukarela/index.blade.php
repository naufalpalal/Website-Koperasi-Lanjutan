@extends('pengurus.index')

@include('pengurus.Simpanan.Sukarela.generate')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-sm border">
    {{-- ALERT --}}
    @if (session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- ACTION BAR --}}
    <div class="flex flex-wrap items-center justify-between mb-4 gap-3">

        {{-- KIRI --}}
        <label for="modal-periode"
            class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 cursor-pointer">
            Pilih Periode
        </label>

        {{-- KANAN --}}
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('pengurus.simpanan.sukarela.download') }}"
                class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded">
                Export Excel
            </a>

            <a href="{{ route('pengurus.simpanan.sukarela.pengajuan') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded">
                Pengajuan Baru
            </a>

            <a href="{{ route('pengurus.simpanan.sukarela.riwayat') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded">
                Riwayat
            </a>
        </div>
    </div>

    {{-- FORM --}}
    <form action="{{ route('pengurus.simpanan.sukarela.update') }}" method="POST">
        @csrf

        {{-- PILIH SEMUA --}}
        <div class="mb-3 flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" id="checkAll"
                onclick="document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked)"
                class="h-4 w-4 text-blue-600 border-gray-300 rounded">
            <label for="checkAll" class="cursor-pointer select-none">
                Pilih semua simpanan
            </label>
        </div>

        {{-- TABEL --}}
        <div class="overflow-auto rounded-lg border">
            <table class="w-full text-sm border-collapse font-normal">

                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-4 py-2 border text-left font-normal">Nama</th>
                        <th class="px-4 py-2 border text-left font-normal">Bulan</th>
                        <th class="px-4 py-2 border text-left font-normal">Tahun</th>
                        <th class="px-4 py-2 border text-left font-normal">Nominal</th>
                        <th class="px-4 py-2 border text-left font-normal">Status</th>
                        <th class="px-4 py-2 border text-center font-normal w-12">✔</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($simpanan as $item)
                        <tr class="hover:bg-gray-50 text-gray-700">
                            <td class="px-4 py-2 border font-light">
                                {{ optional($item->user)->nama ?? '-' }}
                            </td>

                            <td class="px-4 py-2 border font-light">{{ $item->bulan }}</td>
                            <td class="px-4 py-2 border font-light">{{ $item->tahun }}</td>

                            <td class="px-4 py-2 border font-light">
                                Rp {{ number_format($item->nilai, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-2 border">
                                <span class="px-2 py-1 rounded text-xs
                                    @if ($item->status === 'Diajukan') bg-yellow-100 text-yellow-700
                                    @elseif($item->status === 'Disetujui') bg-green-100 text-green-700
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ $item->status }}
                                </span>
                            </td>

                            <td class="px-4 py-2 border text-center">
                                <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                    class="row-check h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500 text-sm">
                                Belum ada pengajuan
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- SIMPAN --}}
        <div class="mt-4">
            <button type="submit"
                onclick="return confirm('Simpan perubahan status?')"
                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm">
                Simpan
            </button>
        </div>

    </form>

</div>
@endsection
