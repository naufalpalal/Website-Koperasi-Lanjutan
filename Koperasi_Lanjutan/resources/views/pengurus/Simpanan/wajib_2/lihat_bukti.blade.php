@extends('pengurus.index')

@section('content')
<div class="container mx-auto px-4 sm:px-6 py-4">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Bukti Pembayaran Gagal Simpanan Wajib</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola bukti transfer simpanan wajib yang gagal</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-300 text-green-800 p-3 mb-6 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- SEARCH --}}
    <div class="mb-5">
        <div class="flex items-center gap-2 w-full md:w-96">
            <input id="searchInput" type="text" placeholder="Cari nama anggota..."
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <button id="btnSearch" type="button"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Cari
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse bg-white shadow-sm rounded-lg overflow-hidden" id="buktiTable">
            <thead class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                <tr>
                    <th class="border-b px-4 py-3 text-left text-sm font-semibold">Nama</th>
                    <th class="border-b px-4 py-3 text-left text-sm font-semibold">Potongan Gagal</th>
                    <th class="border-b px-4 py-3 text-left text-sm font-semibold">Tanggal</th>
                    <th class="border-b px-4 py-3 text-center text-sm font-semibold">Bukti</th>
                    <th class="border-b px-4 py-3 text-sm font-semibold text-center">Aksi</th>
                </tr>
            </thead>

            <tbody id="tableBody">
                @forelse($data as $d)
                    <tr class="text-gray-700 hover:bg-gray-50 transition border-b border-gray-100">
                        <td class="px-4 py-3 text-sm font-medium">{{ $d->user->nama ?? '-' }}</td>

                        <td class="px-4 py-3 text-sm font-semibold text-green-600">
                            Rp {{ number_format($d->nilai, 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-3 text-sm">
                            {{ \Carbon\Carbon::parse($d->created_at)->translatedFormat('d F Y, l') }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            @if($d->bukti_transfer)
                                <a href="{{ asset('storage/bukti_transfer/'.$d->bukti_transfer) }}"
                                   target="_blank"
                                   class="inline-block">
                                    <img src="{{ asset('storage/bukti_transfer/'.$d->bukti_transfer) }}"
                                         class="w-16 h-16 object-cover rounded-lg border hover:shadow-md transition">
                                </a>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('pengurus.simpanan.wajib_2.hapus_bukti', $d->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-3 py-1.5 rounded-lg">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400 italic">
                            Tidak ada data bukti pembayaran gagal.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-6 flex justify-center" id="paginationContainer"></div>
</div>

@endsection
