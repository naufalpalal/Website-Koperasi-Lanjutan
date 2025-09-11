@extends('admin.index')

@section('title', 'Kelola Anggota')
@extends('admin.layouts.navbar')

@section('content')
<div class="container mx-auto pt-12 px-10">
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <h5 class="text-xl font-semibold text-gray-700">Kelola Anggota</h5>
            <a href="{{ route('admin.anggota.create') }}" 
               class="bg-blue-400 hover:bg-blue-500 text-white px-4 py-2 rounded-lg shadow transition">
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
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">Nama</th>
                        <th class="border p-2">NIP</th>
                        <th class="border p-2">No Telepon</th>
                        <th class="border p-2">Alamat</th>
                        <th class="border p-2">Tempat, Tanggal Lahir</th>
                        <th class="border p-2">Simpanan Sukarela</th>
                        <th class="border p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($anggota as $a)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-2 font-semibold text-gray-800">{{ $a->nama }}</td>
                        <td class="border p-2 text-gray-600">{{ $a->nip ?? '-' }}</td>
                        <td class="border p-2 text-gray-600">{{ $a->no_telepon }}</td>
                        <td class="border p-2 text-gray-600">{{ $a->alamat_rumah ?? '-' }}</td>
                        <td class="border p-2 text-gray-600">
                            @if($a->tanggal_lahir)
                                {{ $a->tempat_lahir ?? '-' }}, {{ \Carbon\Carbon::parse($a->tanggal_lahir)->format('d-m-Y') }}
                            @else
                                {{ $a->tempat_lahir ?? '-' }}
                            @endif
                        </td>
                        <td class="border p-2 text-gray-600 text-center">
                            Rp{{ number_format($a->simpanan()->where('type','sukarela')->sum('amount'), 0, ',', '.') }}
                        </td>
                        <td class="border p-2 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('admin.anggota.edit', $a->id) }}" 
                                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-md text-sm shadow flex items-center min-w-[80px] justify-center">
                                    <i class="bi bi-pencil-square mr-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.anggota.destroy', $a->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin ingin menghapus?')" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-md text-sm shadow flex items-center min-w-[80px] justify-center">
                                        <i class="bi bi-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="text-center text-gray-500 py-10">
                Belum ada data anggota
            </div>
        @endif
    </div>
</div>
@endsection
