@extends('pengurus.index')

@section('title', 'Verifikasi Anggota')

@section('content')
<div class="container mx-auto pt-12 px-10">
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <h5 class="text-xl font-semibold text-gray-700">Verifikasi Anggota</h5>
        </div>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        {{-- Daftar Anggota untuk Diverifikasi --}}
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
                        <th class="border p-2">Unit Kerja</th>
                        <th class="border p-2 text-center">Dokumen</th>
                        <th class="border p-2 text-center">Aksi</th>
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
                        <td class="border p-2 text-gray-600">{{ $a->unit_kerja ?? '-' }}</td>
                        
                        {{-- Tombol Lihat Dokumen --}}
                        <td class="border p-2 text-center">
                            @if($a->dokumen_path)
                                <a href="{{ asset('storage/' . $a->dokumen_path) }}" 
                                   target="_blank"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-md text-sm shadow">
                                   <i class="bi bi-file-earmark-text"></i> Lihat Dokumen
                                </a>
                            @else
                                <span class="text-gray-400 text-sm">Tidak ada dokumen</span>
                            @endif
                        </td>

                        {{-- Tombol Verifikasi --}}
                        <td class="border p-2 text-center">
                            <div class="flex justify-center gap-2">
                                {{-- Tombol Setujui --}}
                                <form action="{{ route('pengurus.KelolaAnggota.approve', $a->id) }}" method="POST" onsubmit="return confirm('Setujui anggota ini?')">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md text-sm shadow">
                                        <i class="bi bi-check-circle mr-1"></i> Setuju
                                    </button>
                                </form>

                                {{-- Tombol Tolak --}}
                                <form action="{{ route('pengurus.KelolaAnggota.reject', $a->id) }}" method="POST" onsubmit="return confirm('Tolak anggota ini?')">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm shadow">
                                        <i class="bi bi-x-circle mr-1"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="text-center text-gray-500 py-10">
                Belum ada data anggota untuk diverifikasi
            </div>
        @endif
    </div>
</div>
@endsection
