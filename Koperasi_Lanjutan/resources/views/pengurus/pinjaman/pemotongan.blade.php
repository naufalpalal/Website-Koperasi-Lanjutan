@extends('Pengurus.index')

@section('content')
    <div class="p-6 bg-white rounded-xl shadow-lg">
        {{-- Filter & Tombol --}}
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">

            {{-- Filter --}}
            <form method="GET" action="{{ route('pengurus.pinjaman.pemotongan') }}"
                class="flex items-center gap-3 bg-gray-50 p-3 rounded-lg shadow-sm">

                <div>
                    <label class="text-sm font-medium">Periode:</label>
                    <input type="month" name="periode" value="{{ request('periode', now()->format('Y-m')) }}"
                        class="border rounded px-3 py-1">
                </div>

                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Filter
                </button>

                <a href="{{ route('pengurus.pinjaman.pemotongan') }}" class="text-sm text-gray-600 hover:underline">
                    Reset
                </a>
            </form>

            {{-- Action --}}
            <div class="flex gap-2">
                <a href="{{ route('pengurus.pinjaman.pengajuan') }}"
                    class="bg-blue-500 text-white px-3 py-1.5 rounded-lg text-sm">
                    📄 Pengajuan
                </a>

                <a href="{{ route('pengurus.pinjaman.settings.index') }}"
                    class="bg-green-500 text-white px-3 py-1.5 rounded-lg text-sm">
                    ⚙️ Setting
                </a>

                <a href="{{ route('pengurus.pinjaman.pengajuan-angsuran.index') }}"
                    class="bg-purple-500 text-white px-3 py-1.5 rounded-lg text-sm">
                    🧾 Angsuran
                </a>

                <a href="{{ route('pengurus.pinjaman.download') }}"
                    class="bg-indigo-500 text-white px-3 py-1.5 rounded-lg text-sm">
                    📥 Export
                </a>
            </div>
        </div>

        {{-- FORM SIMPAN --}}
        <form action="{{ route('pengurus.pinjaman.updateStatus') }}" method="POST">
            @csrf

            {{-- PILIH SEMUA --}}
            <div class="mb-3 flex items-center gap-2">
                <input type="checkbox"
                    onclick="document.querySelectorAll('.row-check').forEach(c => c.checked = this.checked)"
                    class="h-5 w-5 rounded border-gray-300 text-green-600 cursor-pointer">
                <span class="text-sm text-gray-700">Pilih semua angsuran</span>
            </div>

            {{-- TABEL --}}
            <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
                <table class="w-full text-sm border-collapse">

                    <thead class="bg-gray-100 text-gray-700 text-center font-normal">
                        <tr>
                            <th class="px-3 py-2 border font-normal">Nama Anggota</th>
                            <th class="px-3 py-2 border font-normal">Nominal</th>
                            <th class="px-3 py-2 border font-normal">Angsuran Ke</th>
                            <th class="px-3 py-2 border font-normal">Jumlah Bayar</th>
                            <th class="px-3 py-2 border font-normal">Jatuh Tempo</th>
                            <th class="px-3 py-2 border font-normal">Pilih</th>
                            <th class="px-3 py-2 border font-normal">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($angsuran as $a)
                                        <tr class="hover:bg-gray-50 border-b">

                                            <td class="px-3 py-2">{{ $a->pinjaman->user->nama }}</td>

                                            <td class="px-3 py-2 text-right">
                                                Rp{{ number_format($a->pinjaman->nominal, 0, ',', '.') }}
                                            </td>

                                            <td class="px-3 py-2 text-center">{{ $a->bulan_ke }}</td>

                                            <td class="px-3 py-2 text-right">
                                                Rp{{ number_format($a->jumlah_bayar, 0, ',', '.') }}
                                            </td>

                                            <td class="px-3 py-2 text-center">
                                                {{ $a->tanggal_bayar
                            ? \Carbon\Carbon::parse($a->tanggal_bayar)->translatedFormat('d F Y')
                            : '-' }}
                                            </td>

                                            <td class="px-3 py-2 text-center">
                                                <input type="checkbox" name="angsuran_ids[]" value="{{ $a->id }}" class="row-check h-5 w-5 rounded border-gray-300
                                   text-green-600 focus:ring-green-500 cursor-pointer">
                                            </td>


                                            <td class="px-3 py-2 text-center">
                                                <span class="px-2 py-1 rounded text-white text-xs
                                                    {{ $a->status === 'lunas' ? 'bg-green-600' : 'bg-red-500' }}">
                                                    {{ ucfirst($a->status) }}
                                                </span>
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-gray-500">
                                    Tidak ada angsuran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- TOMBOL SIMPAN --}}
            <div class="mt-4 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded
                               hover:bg-green-700 transition">
                    Simpan Angsuran Terpilih
                </button>
            </div>

        </form>

    </div>
@endsection