@extends('admin.index')

@section('title', 'Kelola Anggota')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <h5 class="text-xl font-semibold text-gray-700">Kelola Anggota</h5>
            <a href="{{ route('admin.anggota.create') }}" 
               class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg shadow transition">
                <i class="bi bi-plus-circle"></i> Tambah
            </a>
        </div>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Daftar Anggota --}}
        @if($anggota->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($anggota as $a)
            <div class="bg-gray-50 border rounded-xl shadow hover:shadow-lg transition p-5">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-500 text-white text-2xl">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="ml-3">
                        <h5 class="text-lg font-bold text-gray-800">{{ $a->nama }}</h5>
                        <p class="text-sm text-gray-500">{{ $a->nip ?? '-' }}</p>
                    </div>
                </div>
                <ul class="text-sm text-gray-600 mb-3 space-y-1">
                    <li><i class="bi bi-telephone"></i> {{ $a->no_telepon }}</li>
                    <li><i class="bi bi-geo-alt"></i> {{ $a->alamat_rumah ?? '-' }}</li>
                    <li><i class="bi bi-calendar"></i>
                        @if($a->tanggal_lahir)
                            {{ $a->tempat_lahir ?? '-' }}, {{ \Carbon\Carbon::parse($a->tanggal_lahir)->format('d-m-Y') }}
                        @else
                            {{ $a->tempat_lahir ?? '-' }}
                        @endif
                    </li>
                </ul>
                <div class="flex gap-2">
                    <a href="{{ route('admin.anggota.edit', $a->id) }}" 
                       class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-sm shadow">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    <form action="{{ route('admin.anggota.destroy', $a->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Yakin ingin menghapus?')" 
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm shadow">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
            <div class="text-center text-gray-500 py-10">
                Belum ada data anggota
            </div>
        @endif
    </div>
</div>
@endsection
