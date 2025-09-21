@extends('user.index')

@section('content')
<div class="p-6 bg-white rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Ajukan Perubahan Nominal Simpanan Sukarela</h2>

    {{-- Pesan sukses / error --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form pengajuan nominal baru --}}
    <div class="bg-gray-50 p-4 rounded mb-6">
        <form action="{{ route('user.simpanan.sukarela.pengajuan') }}" method="POST">
            @csrf
            <label for="nilai_baru" class="block mb-2 font-medium">Nominal Baru (Rp)</label>
            <input type="number" name="nilai_baru" id="nilai_baru"
                   class="w-full border rounded px-3 py-2 mb-3" required min="1000">

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Ajukan
            </button>
        </form>
    </div>

    {{-- Tabel riwayat pengajuan sebelumnya --}}
    <h3 class="text-lg font-semibold mb-3">Riwayat Pengajuan Sebelumnya</h3>
    <table class="min-w-full border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 border">Bulan</th>
                <th class="px-4 py-2 border">Tahun</th>
                <th class="px-4 py-2 border">Nominal</th>
                <th class="px-4 py-2 border">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuan as $item)
            <tr>
                <td class="px-4 py-2 border">{{ $item->bulan }}</td>
                <td class="px-4 py-2 border">{{ $item->tahun }}</td>
                <td class="px-4 py-2 border">Rp {{ number_format($item->nilai, 0, ',', '.') }}</td>
                <td class="px-4 py-2 border">{{ $item->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4">Belum ada pengajuan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
