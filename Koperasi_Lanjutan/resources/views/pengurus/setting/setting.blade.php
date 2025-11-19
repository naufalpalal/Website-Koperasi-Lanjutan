@extends('pengurus.index')

@section('title', 'Identitas Koperasi')

@section('content')
    <div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded-2xl shadow">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Identitas Koperasi
        </h2>

        {{-- Alert Sukses --}}
        @if(session('success'))
            <div class="mb-6 p-4 text-green-700 bg-green-100 rounded-lg border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        {{-- Alert Error --}}
        @if(session('error'))
            <div class="mb-6 p-4 text-red-700 bg-red-100 rounded-lg border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                {{-- 1. Nama Koperasi --}}
                <div>
                    <label for="nama_koperasi" class="block text-gray-700 font-semibold mb-1">Nama Koperasi:</label>
                    <input type="text" name="nama_koperasi" id="nama_koperasi"
                        value="{{ old('nama_koperasi', $identitas->nama_koperasi ?? '') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border">
                    @error('nama_koperasi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 2. Nama Ketua Koperasi --}}
                <div>
                    <label for="nama_ketua_koperasi" class="block text-gray-700 font-semibold mb-1">Nama Ketua
                        Koperasi:</label>
                    <input type="text" name="nama_ketua_koperasi" id="nama_ketua_koperasi"
                        value="{{ old('nama_ketua_koperasi', $identitas->nama_ketua_koperasi ?? ($ketua->nama ?? '')) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border">
                    @error('nama_ketua_koperasi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 3. Nama Sekretaris Koperasi --}}
                <div>
                    <label for="nama_sekretaris_koperasi" class="block text-gray-700 font-semibold mb-1">Nama Sekretaris
                        Koperasi:</label>
                    <input type="text" name="nama_sekretaris_koperasi" id="nama_sekretaris_koperasi"
                        value="{{ old('nama_sekretaris_koperasi', $identitas->nama_sekretaris_koperasi ?? ($sekretaris->nama ?? '')) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border">
                    @error('nama_sekretaris_koperasi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 4. Nama Bendahara Koperasi (DINAMIS) --}}
                <div class="p-5 bg-gray-50 rounded-xl border border-gray-200">
                    <label class="block text-gray-700 font-bold mb-3 border-b pb-2">Daftar Bendahara Koperasi</label>

                    <div id="bendahara-wrapper" class="space-y-3">
                        {{-- A. SKENARIO: VALIDASI ERROR (Ambil dari input terakhir user) --}}
                        @if(old('nama_bendahara_koperasi'))
                            @foreach(old('nama_bendahara_koperasi') as $index => $oldValue)
                                <div class="flex items-center gap-3 bendahara-item">
                                    <input type="text" name="nama_bendahara_koperasi[]" value="{{ $oldValue }}"
                                        placeholder="Masukkan Nama User Bendahara"
                                        class="flex-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border">

                                    @if($index > 0)
                                        <button type="button" onclick="removeInput(this)"
                                            class="shrink-0 bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-800 border border-red-200 px-4 py-2.5 rounded-lg transition font-semibold">
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            @endforeach

                            {{-- B. SKENARIO: DATA DARI DATABASE --}}
                        @elseif(isset($list_bendahara) && count($list_bendahara) > 0)
                            @foreach($list_bendahara as $index => $bendahara)
                                <div class="flex items-center gap-3 bendahara-item">
                                    {{-- PERBAIKAN DI SINI: --}}
                                    {{-- Cek apakah data berupa object (->nama) atau string langsung --}}
                                    <input type="text" name="nama_bendahara_koperasi[]"
                                        value="{{ is_object($bendahara) ? $bendahara->nama : $bendahara }}"
                                        placeholder="Masukkan Nama User Bendahara"
                                        class="flex-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border">

                                    @if($index > 0)
                                        <button type="button" onclick="removeInput(this)"
                                            class="shrink-0 bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-800 border border-red-200 px-4 py-2.5 rounded-lg transition font-semibold">
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            @endforeach

                            {{-- C. SKENARIO: KOSONG (Belum ada data) --}}
                        @else
                            <div class="flex items-center gap-3 bendahara-item">
                                <input type="text" name="nama_bendahara_koperasi[]" placeholder="Masukkan Nama User Bendahara"
                                    class="flex-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border">
                            </div>
                        @endif
                    </div>

                    {{-- Tombol Tambah --}}
                    <button type="button" onclick="addBendahara()"
                        class="mt-4 py-2 px-4 bg-white border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 transition text-sm font-semibold flex items-center gap-2 shadow-sm w-full justify-center sm:w-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Bendahara Lain
                    </button>

                    @error('nama_bendahara_koperasi')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 5. Nama Bendahara Gaji --}}
                <div>
                    <label for="bendahara_gaji" class="block text-gray-700 font-semibold mb-1">Nama Bendahara Gaji:</label>
                    <input type="text" name="bendahara_gaji" id="bendahara_gaji"
                        value="{{ old('bendahara_gaji', $identitas->bendahara_gaji ?? ($bendahara_gaji->nama ?? '')) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border">
                    @error('bendahara_gaji')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 6. Wakil Direktur (Wadir) --}}
                <div>
                    <label for="nama_wadir" class="block text-gray-700 font-semibold mb-1">Wakil Direktur (Wadir):</label>
                    <input type="text" name="nama_wadir" id="nama_wadir"
                        value="{{ old('nama_wadir', $identitas->nama_wadir ?? ($wadir->nama ?? '')) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2.5 border">
                    @error('nama_wadir')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-10 flex justify-between items-center pt-6 border-t border-gray-200">
                <a href="{{ route('pengurus.dashboard.index') }}"
                    class="bg-gray-500 text-white px-6 py-2.5 rounded-lg hover:bg-gray-600 transition font-medium shadow-sm">
                    &larr; Kembali
                </a>

                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition font-medium shadow-lg shadow-blue-500/30">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

@endsection