@extends('admin.index')
@extends('admin.layouts.navbar')

@section('title', 'Ubah Nominal Simpanan Wajib')

@section('content')
<div class="container mx-auto pt-12 px-10">
    <div class="bg-white rounded-xl shadow p-6 max-w-lg mx-auto">
        <h5 class="text-xl font-semibold text-gray-700 mb-4">Ubah Nominal Simpanan Wajib</h5>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Ubah Nominal --}}
        <form action="{{ route('admin.nominal_wajib.update') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="nominal" class="block text-sm font-medium text-gray-700">Nominal Baru</label>
                <input type="number" name="nominal" id="nominal" 
                       class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2 focus:ring-blue-400 focus:border-blue-400"
                       placeholder="Masukkan nominal baru"
                       required>
            </div>

            <div class="mb-4">
                <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                <input type="number" name="tahun" id="tahun"
                       class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-2 focus:ring-blue-400 focus:border-blue-400"
                       placeholder="Contoh: 2025">
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.nominal_wajib.index') }}" 
                   class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg shadow">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
                    Kirim
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
