@extends('pengurus.index')

@section('title', 'Identitas Koperasi')

@section('content')
    <div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Identitas Koperasi
        </h2>

        @if(session('success'))
            <div class="mb-6 p-4 text-green-700 bg-green-100 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form Update Identitas --}}
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-5">

                {{-- Nama Koperasi --}}
                <div>
                    <label for="nama_koperasi" class="block text-gray-600 font-semibold">Nama Koperasi:</label>
                    <input type="text" name="nama_koperasi" id="nama_koperasi"
                        value="{{ old('nama_koperasi', $identitas->nama_koperasi ?? '') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('nama_koperasi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Ketua Koperasi --}}
                <div>
                    <label for="nama_ketua_koperasi" class="block text-gray-600 font-semibold">Nama Ketua Koperasi:</label>
                    <input type="text" name="nama_ketua_koperasi" id="nama_ketua_koperasi"
                        value="{{ old('nama_ketua_koperasi', $identitas->nama_ketua_koperasi ?? '') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('nama_ketua_koperasi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Sekretaris Koperasi --}}
                <div>
                    <label for="nama_sekretaris_koperasi" class="block text-gray-600 font-semibold">Nama Sekretaris
                        Koperasi:</label>
                    <input type="text" name="nama_sekretaris_koperasi" id="nama_sekretaris_koperasi"
                        value="{{ old('nama_sekretaris_koperasi', $identitas->nama_sekretaris_koperasi ?? '') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('nama_sekretaris_koperasi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Bendahara Koperasi --}}
                <div>
                    <label for="nama_bendahara_koperasi" class="block text-gray-600 font-semibold">Nama Bendahara
                        Koperasi:</label>
                    <input type="text" name="nama_bendahara_koperasi" id="nama_bendahara_koperasi"
                        value="{{ old('nama_bendahara_koperasi', $identitas->nama_bendahara_koperasi ?? '') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('nama_bendahara_koperasi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama Bendahara Gaji --}}
                <div>
                    <label for="bendahara_gaji" class="block text-gray-600 font-semibold">Nama Bendahara
                        Gaji:</label>
                    <input type="text" name="bendahara_gaji" id="bendahara_gaji"
                        value="{{ old('bendahara_gaji', $identitas->bendahara_gaji ?? '') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('bendahara_gaji')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Wakil Direktur (Wadir) --}}
                <div>
                    <label for="nama_wadir" class="block text-gray-600 font-semibold">Wakil Direktur (Wadir):</label>
                    <input type="text" name="nama_wadir" id="nama_wadir"
                        value="{{ old('nama_wadir', $identitas->nama_wadir ?? '') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('nama_wadir')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-8 flex justify-between">
                <a href="{{ route('pengurus.dashboard.index') }}"
                    class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition">
                    Kembali
                </a>

                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection