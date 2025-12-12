@extends('Pengurus.index')

@section('content')
    <div class="p-6 bg-white rounded-xl shadow-lg">

        {{-- Judul --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold flex items-center gap-2">
                Laporan Pemotongan Pinjaman
            </h2>
        </div>

        {{-- Filter & Tombol Aksi --}}
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">

            {{-- FILTER PERIODE --}}
            <form method="GET" action="{{ route('pengurus.pinjaman.pemotongan') }}"
                class="flex items-center gap-3 bg-gray-50 p-3 rounded-lg shadow-sm">
                @csrf

                <div>
                    <label class="text-sm font-medium">Periode:</label>
                    <input id="periode" name="periode" type="month" value="{{ request('periode', now()->format('Y-m')) }}"
                        class="border rounded px-3 py-1 focus:ring-blue-400 focus:border-blue-400">
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition">
                    Filter
                </button>

                <a href="{{ route('pengurus.pinjaman.pemotongan') }}"
                    class="text-sm text-gray-600 hover:text-gray-900 hover:underline">
                    Reset
                </a>
            </form>

            {{-- ACTION BUTTON --}}
            <div class="flex flex-wrap items-center gap-2">

                <a href="{{ route('pengurus.pinjaman.pengajuan') }}"
                    class="flex items-center gap-1.5 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm shadow-sm transition">
                    📄 <span>Daftar Pengajuan</span>
                </a>

                <a href="{{ route('pengurus.pinjaman.settings.index') }}"
                    class="flex items-center gap-1.5 bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-sm shadow-sm transition">
                    ⚙️ <span>Setting Tenor & Bunga</span>
                </a>

                <a href="{{ route('pengurus.pinjaman.pengajuan-angsuran.index') }}"
                    class="flex items-center gap-1.5 bg-purple-500 hover:bg-purple-600 text-white px-3 py-1.5 rounded-lg text-sm shadow-sm transition">
                    🧾 <span>Pengajuan Angsuran</span>
                </a>

                <a href="{{ route('pengurus.pinjaman.download') }}"
                    class="flex items-center gap-1.5 bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm shadow-sm transition">
                    📥 <span>Export Excel</span>
                </a>

            </div>

        </div> {{-- end filter & buttons --}}

        {{-- TABEL --}}
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
            <table class="w-full text-sm border-collapse">

                <thead class="bg-gray-100 text-gray-700 text-center">
                    <tr>
                        <th class="px-3 py-2 border font-normal">No</th>
                        <th class="px-3 py-2 border font-normal">Nama Anggota</th>
                        <th class="px-3 py-2 border font-normal">Nominal Pinjaman</th>
                        <th class="px-3 py-2 border font-normal">Angsuran Ke</th>
                        <th class="px-3 py-2 border font-normal">Jumlah Bayar</th>
                        <th class="px-3 py-2 border font-normal">Jatuh Tempo</th>
                        <th class="px-3 py-2 border font-normal">Catat Lunas</th>
                        <th class="px-3 py-2 border font-normal">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @if ($angsuran->count() > 0)

                        @foreach ($angsuran as $idx => $a)
                                <tr id="row-{{ $a->id }}" class="hover:bg-gray-50 transition border-b">

                                    <td class="px-3 py-2 text-center">{{ $idx + 1 }}</td>

                                    <td class="px-3 py-2">
                                        {{ $a->pinjaman->user->nama ?? '-' }}
                                    </td>

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
                                        <form action="{{ route('pengurus.pinjaman.updateStatus', $a->id) }}" method="POST"
                                            class="flex items-center justify-center gap-2">
                                            @csrf

                                            <input type="hidden" name="status"
                                                value="{{ $a->status === 'lunas' ? 'belum lunas' : 'lunas' }}">

                                            @if ($a->status !== 'lunas')
                                                <input type="number" name="diskon" placeholder="Diskon (Rp)"
                                                    class="w-24 text-xs border rounded px-2 py-1 focus:ring-blue-400" min="0">
                                            @else
                                                <div class="text-xs text-green-600 font-semibold">
                                                    {{ $a->diskon > 0 ? 'Diskon: Rp' . number_format($a->diskon, 0, ',', '.') : '-' }}
                                                </div>
                                            @endif

                                            <button type="submit"
                                                class="w-8 h-8 flex items-center justify-center rounded text-white shadow-sm
                                                            {{ $a->status === 'lunas' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
                                                {{ $a->status === 'lunas' ? '✔' : '✖' }}
                                            </button>
                                        </form>
                                    </td>

                                    <td class="px-3 py-2 text-center">
                                        <span class="px-2 py-1 rounded text-white
                                                    {{ $a->status === 'lunas' ? 'bg-green-600' : 'bg-red-500' }}">
                                            {{ ucfirst($a->status) }}
                                        </span>
                                    </td>

                                </tr>
                        @endforeach

                    @else
                        {{-- BARIS KOSONG --}}
                        <tr>
                            <td colspan="8" class="py-10">
                                <div class="flex flex-col items-center text-center text-gray-600">
                                    <p class="text-lg font-semibold">Tidak ada potongan pinjaman</p>
                                    <p class="text-sm text-gray-500 mt-1">Periode ini bersih tanpa angsuran dipotong.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>

            </table>
        </div>

    </div>
@endsection