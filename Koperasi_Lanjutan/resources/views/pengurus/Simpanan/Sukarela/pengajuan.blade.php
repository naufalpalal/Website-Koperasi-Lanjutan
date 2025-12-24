@extends('pengurus.index')

@section('title', 'Persetujuan Simpanan Sukarela')

@section('content')
<div class="container mx-auto pt-12 px-10">
    <div class="bg-white rounded-xl shadow p-6">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-medium text-gray-700">
                Persetujuan Pengajuan Simpanan Sukarela
            </h1>
            <a href="{{ route('pengurus.simpanan.sukarela.index') }}"
                class="text-blue-600 hover:underline">
                ← Kembali
            </a>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- LIST CARD --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @forelse($pengajuan as $item)
                <div class="border rounded-lg shadow-sm p-5 hover:shadow-md transition">

                    {{-- Nama --}}
                    <h3 class="text-lg font-medium text-gray-800 mb-1">
                        {{ $item->user->nama }}
                    </h3>

                    {{-- Periode --}}
                    <p class="text-sm text-gray-500 mb-3">
                        Periode: {{ $item->bulan }} {{ $item->tahun }}
                    </p>

                    {{-- Nominal --}}
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Nominal</p>
                        <p class="text-xl text-gray-800 font-semibold">
                            Rp {{ number_format($item->nilai, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Status --}}
                    <div class="mb-4">
                        @if ($item->status === 'Pending')
                            <span class="inline-block px-3 py-1 text-sm rounded bg-yellow-100 text-yellow-700">
                                Pending
                            </span>
                        @elseif ($item->status === 'Disetujui')
                            <span class="inline-block px-3 py-1 text-sm rounded bg-green-100 text-green-700">
                                Disetujui
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 text-sm rounded bg-red-100 text-red-700">
                                Ditolak
                            </span>
                        @endif
                    </div>

                    {{-- Aksi --}}
                    @if ($item->status === 'Pending')
                        <div class="flex gap-2">

                            <form method="POST"
                                action="{{ route('pengurus.simpanan.sukarela.approve', $item->id) }}">
                                @csrf
                                <button type="submit"
                                    class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-sm">
                                    Setujui
                                </button>
                            </form>

                            <form method="POST"
                                action="{{ route('pengurus.simpanan.sukarela.reject', $item->id) }}">
                                @csrf
                                <button type="submit"
                                    class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm">
                                    Tolak
                                </button>
                            </form>

                        </div>
                    @else
                        <p class="text-sm text-gray-400 italic text-center">
                            Tidak ada aksi
                        </p>
                    @endif

                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 py-10">
                    Tidak ada pengajuan simpanan sukarela.
                </div>
            @endforelse

        </div>

    </div>
</div>
@endsection
