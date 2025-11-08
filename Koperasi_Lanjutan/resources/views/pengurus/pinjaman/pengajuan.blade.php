@extends('Pengurus.index')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">📋 Daftar Pengajuan Pinjaman Anggota</h1>
    <div class="space-y-4">
        <div class="bg-gray-100 border border-gray-200 rounded-lg p-4">
            <div class="grid grid-cols-3 gap-4">
                <div class="font-semibold">Nama Anggota</div>
                <div class="font-semibold">Tanggal Pengajuan</div>
                <div class="font-semibold">Nominal</div>
            </div>
        </div>
        @forelse($pinjaman as $item)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition p-4 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-semibold text-lg">
                        {{ strtoupper(substr($item->user->nama, 0, 1)) }}
                    </div>

                    <div>
                        <div class="text-gray-800 font-medium">{{ $item->user->nama }}</div>
                        <div class="text-sm text-gray-500">
                            @if(isset($item->created_at))
                                {{ $item->created_at->format('d M Y') }} •
                            @endif
                            Rp{{ number_format($item->nominal, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @if(isset($item->status))
                        @php
                            $status = strtolower($item->status);
                            $badgeColor = $status === 'disetujui' ? 'bg-green-100 text-green-800' : ($status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800');
                        @endphp
                        <span class="px-3 py-1 text-sm rounded-full font-medium {{ $badgeColor }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    @endif

                    <a href="{{ route('pengurus.pinjaman.show', $item->id) }}" class="inline-flex items-center px-3 py-2 bg-white border border-gray-200 rounded-md text-sm text-indigo-600 hover:bg-indigo-50">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white border border-dashed border-gray-200 rounded-lg p-8 text-center text-gray-600">
                Belum ada pengajuan pinjaman.
            </div>
        @endforelse
    </div>

    @if(method_exists($pinjaman, 'links'))
        <div class="mt-6">
            {{ $pinjaman->links() }}
        </div>
    @endif
</div>
@endsection
