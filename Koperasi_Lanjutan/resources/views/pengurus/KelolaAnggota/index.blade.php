@extends('pengurus.index')

@section('title', 'Kelola Anggota')

{{-- Pastikan Anda memiliki section 'content' di layout parent Anda --}}
@section('content')
    @php
        use Illuminate\Pagination\LengthAwarePaginator;
        use Illuminate\Support\Collection;
        // Asumsi $anggota sudah di-paginate oleh Controller: $anggota = $query->paginate(5)->withQueryString();

        // Variabel $paginated sudah disediakan oleh Controller
        if ($anggota instanceof LengthAwarePaginator) {
            $paginated = $anggota;
        } else {
            // Ini hanya untuk fallback jika Anda tidak mem-paginate di Controller
            $perPage = 5;
            $currentPage = request()->get('page', 1);
            $collection = $anggota instanceof Collection ? $anggota : collect($anggota);
            $total = $collection->count();
            $results = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();
            $paginated = new LengthAwarePaginator($results, $total, $perPage, $currentPage, [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);
        }
    @endphp

    <div class="container mx-auto pt-12 px-4 lg:px-10">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center border-b pb-4 mb-6 gap-4">
                <h5 class="text-2xl font-semibold text-gray-700 dark:text-gray-100">Kelola Anggota</h5>
                <div class="flex items-center gap-3">
                    <a href="{{ route('pengurus.KelolaAnggota.create') }}" class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Tambah</span>
                    </a>
                </div>
            </div>

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Search Input (Tanpa Form tag untuk AJAX) --}}
<form method="GET" action="{{ route('pengurus.KelolaAnggota.index') }}" class="flex items-center gap-2 w-full md:w-1/2 mb-4">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari anggota..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" />
    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Cari</button>
    <a href="{{ route('pengurus.KelolaAnggota.index') }}" class="text-sm text-gray-500 ml-2">Reset</a>
</form>


            {{-- CONTAINER UTAMA HASIL DATA: Bagian ini yang akan diganti oleh AJAX --}}
            <div id="anggotaResultsContainer">
                
                @if($paginated->count())
                    {{-- Desktop / Tablet: Tabel --}}
                    <div class="hidden md:block">
                        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                            <table class="min-w-full table-auto">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr class="text-left">
                                        <th class="px-6 py-3 text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">NIP</th>
                                        <th class="px-6 py-3 text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">No Telepon</th>
                                        <th class="px-6 py-3 text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider max-w-[260px]">Alamat</th>
                                        <th class="px-6 py-3 text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tempat, Tgl Lahir</th>
                                        <th class="px-6 py-3 text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Unit Kerja</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-100">
                                    @foreach($paginated as $a)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                            <td class="px-6 py-4 align-middle max-w-[220px] truncate" title="{{ $a->nama }}">{{ $a->nama }}</td>
                                            <td class="px-6 py-4 align-middle text-gray-600 dark:text-gray-300">{{ $a->nip ?? '-' }}</td>
                                            <td class="px-6 py-4 align-middle text-gray-600 dark:text-gray-300">{{ $a->no_telepon }}</td>
                                            <td class="px-6 py-4 align-middle text-gray-600 dark:text-gray-300 max-w-[300px] truncate" title="{{ $a->alamat_rumah ?? '-' }}">{{ $a->alamat_rumah ?? '-' }}</td>
                                            <td class="px-6 py-4 align-middle text-gray-600 dark:text-gray-300">
                                                @if($a->tanggal_lahir)
                                                    {{ $a->tempat_lahir ?? '-' }}, {{ \Carbon\Carbon::parse($a->tanggal_lahir)->format('d-m-Y') }}
                                                @else
                                                    {{ $a->tempat_lahir ?? '-' }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 align-middle text-gray-600 dark:text-gray-300">{{ $a->unit_kerja ?? '-' }}</td>
                                            <td class="px-6 py-4 text-center align-middle">
                                                <div class="inline-flex items-center gap-2 justify-center">
                                                    <a href="{{ route('pengurus.KelolaAnggota.edit', $a->id) }}" title="Edit" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-2 rounded-md text-sm shadow">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                        <span class="hidden xl:inline">Edit</span>
                                                    </a>

                                                    <form action="{{ route('pengurus.KelolaAnggota.destroy', $a->id) }}" method="POST" class="m-0 p-0 inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button onclick="return confirm('Yakin ingin menghapus?')" title="Hapus" class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm shadow">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v1H9V4a1 1 0 011-1z"/></svg>
                                                            <span class="hidden xl:inline">Hapus</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Mobile: Card list --}}
                    <div id="mobileList" class="md:hidden space-y-4">
                        @foreach($paginated as $a)
                            <div class="border rounded-lg p-4 shadow-sm bg-white dark:bg-gray-800">
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex-1">
                                        <div class="text-base font-semibold text-gray-800 dark:text-gray-100 truncate">{{ $a->nama }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">NIP: {{ $a->nip ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Tel: {{ $a->no_telepon }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate mt-1" title="{{ $a->alamat_rumah ?? '-' }}">{{ $a->alamat_rumah ?? '-' }}</div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        <a href="{{ route('pengurus.KelolaAnggota.edit', $a->id) }}" class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1 rounded-md text-xs">Edit</a>
                                        <form action="{{ route('pengurus.KelolaAnggota.destroy', $a->id) }}" method="POST" class="m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Yakin ingin menghapus?')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination and info --}}
                    <div class="mt-6 flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            Menampilkan <strong>{{ $paginated->firstItem() ?? 0 }}</strong> - <strong>{{ $paginated->lastItem() ?? 0 }}</strong> dari <strong>{{ $paginated->total() }}</strong> anggota
                        </div>
                        <div>
                            @if($paginated->lastPage() > 1)
                                <div class="flex justify-center">
                                    <nav class="inline-flex flex-wrap items-center gap-1" role="navigation" aria-label="Pagination Navigation">
                                        {{-- Previous --}}
                                        @if($paginated->currentPage() > 1)
                                            <a href="{{ $paginated->url($paginated->currentPage()-1) }}" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 rounded-l-md">Prev</a>
                                        @else
                                            <span class="px-3 py-2 bg-gray-100 text-gray-400 border border-gray-200 rounded-l-md">Prev</span>
                                        @endif

                                        @php
                                            $last = $paginated->lastPage();
                                            $current = $paginated->currentPage();
                                            $start = max(1, $current - 2);
                                            $end = min($last, $current + 2);
                                        @endphp

                                        {{-- First page and leading ellipsis --}}
                                        @if($start > 1)
                                            <a href="{{ $paginated->url(1) }}" class="px-3 py-2 bg-white dark:bg-gray-700 border-t border-b border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50">1</a>
                                            @if($start > 2)
                                                <span class="px-2">&hellip;</span>
                                            @endif
                                        @endif

                                        {{-- Page Numbers (window) --}}
                                        @for($i = $start; $i <= $end; $i++)
                                            @if($i == $current)
                                                <span aria-current="page" class="px-3 py-2 bg-blue-600 text-white border-t border-b border-blue-600">{{ $i }}</span>
                                            @else
                                                <a href="{{ $paginated->url($i) }}" class="px-3 py-2 bg-white dark:bg-gray-700 border-t border-b border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50">{{ $i }}</a>
                                            @endif
                                        @endfor

                                        {{-- Trailing ellipsis and last page --}}
                                        @if($end < $last)
                                            @if($end < $last - 1)
                                                <span class="px-2">&hellip;</span>
                                            @endif
                                            <a href="{{ $paginated->url($last) }}" class="px-3 py-2 bg-white dark:bg-gray-700 border-t border-b border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50">{{ $last }}</a>
                                        @endif

                                        {{-- Next --}}
                                        @if($paginated->currentPage() < $paginated->lastPage())
                                            <a href="{{ $paginated->url($paginated->currentPage()+1) }}" class="px-3 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 rounded-r-md">Next</a>
                                        @else
                                            <span class="px-3 py-2 bg-gray-100 text-gray-400 border border-gray-200 rounded-r-md">Next</span>
                                        @endif
                                    </nav>
                                </div>
                            @endif
                        </div>
                    </div>

                @else
                    <div class="text-center text-gray-500 py-10">
                        Belum ada data anggota
                    </div>
                @endif
                
            </div> 
            {{-- AKHIR CONTAINER UTAMA --}}

        </div>
    </div>
@endsection