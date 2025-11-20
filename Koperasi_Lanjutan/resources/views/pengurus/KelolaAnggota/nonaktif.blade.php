@extends('pengurus.index')

@section('title', 'Anggota Tidak Aktif')

@section('content')
<div class="container mx-auto pt-12 px-4 lg:px-10">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center border-b pb-4 mb-6 gap-4">
            <h5 class="text-2xl font-semibold text-gray-700 dark:text-gray-100">Anggota Tidak Aktif</h5>
            <div class="flex items-center gap-3">
                <a href="{{ route('pengurus.anggota.download') }}" class="text-green-600 hover:text-green-800 transition" title="Download">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M19.5 21a3 3 0..." clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Navigasi antar halaman --}}
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('pengurus.anggota.index') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200">
               Anggota Aktif
            </a>
            <a href="{{ route('pengurus.anggota.nonaktif') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white">
               Anggota Tidak Aktif
            </a>
        </div>

        {{-- Form pencarian --}}
        <form method="GET" action="{{ route('pengurus.anggota.nonaktif') }}" class="flex items-center gap-2 w-full md:w-1/2 mb-4">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari anggota..."
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" />
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Cari</button>
            <a href="{{ route('pengurus.anggota.nonaktif') }}" class="text-sm text-gray-500 ml-2">Reset</a>
        </form>

        {{-- Info jumlah anggota --}}
        <div class="text-sm text-gray-600 dark:text-gray-300 mb-4">
            Menampilkan <strong>{{ $anggota->firstItem() ?? 0 }}</strong> - <strong>{{ $anggota->lastItem() ?? 0 }}</strong> dari <strong>{{ $anggota->total() }}</strong> anggota
        </div>

        {{-- Daftar anggota --}}
        @if($anggota->count())
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr class="text-left">
                            <th class="px-6 py-4 text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Nama</th>
                            <th class="px-6 py-4 text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">NIP</th>
                            <th class="px-6 py-4 text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">No Telepon</th>
                            <th class="px-6 py-4 text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Alamat</th>
                            <th class="px-6 py-4 text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Tempat, Tgl Lahir</th>
                            <th class="px-6 py-4 text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Unit Kerja</th>
                            <th class="px-6 py-4 text-xs font-medium text-gray-600 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                            <th class="px-6 py-4 text-xs font-medium text-gray-600 uppercase tracking-wider text-center whitespace-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($anggota as $a)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $a->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $a->nip ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $a->no_telepon }}</td>
                                <td class="px-6 py-4 max-w-xs truncate" title="{{ $a->alamat_rumah ?? '-' }}">{{ $a->alamat_rumah ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $a->tempat_lahir ?? '-' }}
                                    @if($a->tanggal_lahir)
                                        , {{ \Carbon\Carbon::parse($a->tanggal_lahir)->format('d-m-Y') }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate" title="{{ $a->unit_kerja ?? '-' }}">{{ $a->unit_kerja ?? '-' }}</td>
                                {{-- Tombol Aktifkan Kembali --}}
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <form action="{{ route('pengurus.anggota.restore', $a->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="flex justify-center items-center gap-1 bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-md text-sm shadow w-full mt-1">
                                            Aktifkan Kembali
                                        </button>
                                    </form>
                                </td>
                                {{-- Status --}}
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @if($a->status == 'aktif')
                                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Custom dengan Tailwind CSS --}}
            <div class="mt-8">
                @if ($anggota->hasPages())
                    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between px-4">
                        {{-- Mobile Version --}}
                        <div class="flex justify-between flex-1 sm:hidden gap-3">
                            @if ($anggota->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-not-allowed rounded-lg">
                                    Sebelumnya
                                </span>
                            @else
                                <a href="{{ $anggota->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                    Sebelumnya
                                </a>
                            @endif

                            @if ($anggota->hasMorePages())
                                <a href="{{ $anggota->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                    Selanjutnya
                                </a>
                            @else
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-not-allowed rounded-lg">
                                    Selanjutnya
                                </span>
                            @endif
                        </div>

                        {{-- Desktop Version --}}
                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 leading-5">
                                    Showing
                                    <span class="font-medium">{{ $anggota->firstItem() ?? 0 }}</span>
                                    to
                                    <span class="font-medium">{{ $anggota->lastItem() ?? 0 }}</span>
                                    of
                                    <span class="font-medium">{{ $anggota->total() }}</span>
                                    results
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                {{-- Previous Button --}}
                                @if ($anggota->onFirstPage())
                                    <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-not-allowed rounded-lg">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @else
                                    <a href="{{ $anggota->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @endif

                                {{-- Page Numbers --}}
                                @foreach ($anggota->links()->elements[0] as $page => $url)
                                    @if ($page == $anggota->currentPage())
                                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-lg">
                                            {{ $page }}
                                        </span>
                                    @elseif (is_string($page))
                                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach

                                {{-- Next Button --}}
                                @if ($anggota->hasMorePages())
                                    <a href="{{ $anggota->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-not-allowed rounded-lg">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </nav>
                @endif
            </div>
        @else
            <div class="text-center text-gray-500 py-10">Tidak ada anggota tidak aktif</div>
        @endif
    </div>
</div>
@endsection