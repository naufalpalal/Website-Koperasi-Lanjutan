@extends('pengurus.index')

@include('pengurus.Simpanan.Sukarela.generate')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow-sm border">

        {{-- Judul --}}
        <h2 class="text-2xl font-semibold mb-6 text-gray-700">
            Persetujuan Simpanan Sukarela
        </h2>

        {{-- 🔔 ALERT SECTION --}}
        @if (session('success'))
            <div class="mb-6 p-3 rounded-lg bg-green-100 border border-green-300 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-3 rounded-lg bg-red-100 border border-red-300 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-3 rounded-lg bg-yellow-100 border border-yellow-300 text-yellow-800">
                <ul class="list-disc ms-6">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- END ALERT --}}

        {{-- Wrapper tombol --}}
        <div class="flex flex-wrap items-center justify-between mb-6 gap-4">

            {{-- Tombol kiri --}}
            <label for="modal-periode" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow-sm 
                   hover:bg-blue-700 transition cursor-pointer font-medium">
                Pilih Periode
            </label>

            {{-- Tombol kanan --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('pengurus.simpanan.sukarela.download') }}"
                    class="bg-purple-600 hover:bg-purple-700 text-white py-1.5 px-3 rounded-md text-sm shadow transition">
                    📥 Export Excel
                </a>
                <a href="{{ route('pengurus.simpanan.sukarela.pengajuan') }}"
                    class="bg-green-600 hover:bg-green-700 text-white py-1.5 px-3 rounded-md text-sm shadow transition">
                    🔍 Lihat Pengajuan Baru
                </a>

                <a href="{{ route('pengurus.simpanan.sukarela.riwayat') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white py-1.5 px-3 rounded-md text-sm shadow transition">
                    ✏️ Lihat Riwayat
                </a>
            </div>
        </div>

        {{-- Tabel --}}
        <form action="{{ route('pengurus.simpanan.sukarela.update') }}" method="POST">
            @csrf

            <div class="overflow-auto rounded-lg border shadow-sm">
                <table class="w-full text-sm border-collapse">

                    <thead class="bg-gray-100 text-gray-700 border-b">
                        <tr>
                            <th class="px-4 py-2 border text-left">Nama Anggota</th>
                            <th class="px-4 py-2 border text-left">Bulan</th>
                            <th class="px-4 py-2 border text-left">Tahun</th>
                            <th class="px-4 py-2 border text-left">Nominal</th>
                            <th class="px-4 py-2 border text-left">Status</th>
                            <th class="px-4 py-2 border text-center w-12">
                                <input type="checkbox" id="checkAll">
                                Pilih Semua
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($simpanan as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border">
                                    {{ optional($item->user)->nama ?? '-' }}
                                </td>
                                <td class="px-4 py-2 border">{{ $item->bulan }}</td>
                                <td class="px-4 py-2 border">{{ $item->tahun }}</td>
                                <td class="px-4 py-2 border font-semibold text-gray-800">
                                    Rp {{ number_format($item->nilai, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2 border">
                                    <span class="px-3 py-1 rounded text-xs
                                            @if ($item->status === 'Diajukan') bg-yellow-100 text-yellow-700
                                            @elseif($item->status === 'Disetujui') bg-green-100 text-green-700
                                            @else bg-gray-100 text-gray-600 @endif">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                  <td class="px-4 py-2 border text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">
                                    Belum ada pengajuan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Tombol Simpan --}}
            <div class="mt-4">
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyimpan perubahan status?')"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                    Simpan
                </button>
            </div>

        </form>
    </div>
@endsection