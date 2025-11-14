@extends('pengurus.index')

@section('title', 'Tambah Anggota')

@section('content')
    <div class="max-w-4xl mx-auto mt-8 px-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-lg p-8">
            {{-- Header --}}
            <div class="flex justify-between items-center gap-4 border-b pb-4 mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-50 to-cyan-50 text-indigo-700 px-3 py-1 rounded-md font-semibold shadow-sm">
                        <svg class="w-5 h-5 text-indigo-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12a4 4 0 100-8 4 4 0 000 8z" />
                            <path d="M4 20a8 8 0 0116 0v1H4v-1z" opacity="0.9" />
                        </svg>
                        <span>Tambah Anggota</span>
                    </div>
                </div>
                <a href="{{ route('pengurus.anggota.index') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>

            {{-- Error Alert --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('pengurus.anggota.store') }}" method="POST" novalidate>
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:divide-x md:divide-gray-100">
                    {{-- Kolom Kiri --}}
                    <div class="md:px-6 space-y-6">
                        {{-- Nama --}}
                        <div>
                            <label for="nama" class="block font-semibold text-gray-700">Nama <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                                class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        </div>

                        {{-- No Telepon --}}
                        <div>
                            <label for="no_telepon" class="block font-semibold text-gray-700">No Telepon <span
                                    class="text-red-500">*</span></label>
                            <input type="tel" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" required
                                placeholder="08xxxxxxxxxx"
                                class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        </div>

                        {{-- NIP --}}
                        <div>
                            <label for="nip" class="block font-semibold text-gray-700">NIP</label>
                            <input type="text" id="nip" name="nip" value="{{ old('nip') }}" placeholder="(opsional)"
                                class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        </div>

                        {{-- Unit Kerja --}}
                        <div>
                            <label for="unit_kerja" class="block font-semibold text-gray-700">Unit Kerja</label>
                            <input type="text" id="unit_kerja" name="unit_kerja" value="{{ old('unit_kerja') }}"
                                placeholder="Bagian / Unit"
                                class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="md:px-6 space-y-6">
                        {{-- Tempat Lahir --}}
                        <div>
                            <label for="tempat_lahir" class="block font-semibold text-gray-700">Tempat Lahir</label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                placeholder="Contoh: Banyuwangi"
                                class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label for="tanggal_lahir" class="block font-semibold text-gray-700">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <label for="alamat_rumah" class="block font-semibold text-gray-700">Alamat Rumah</label>
                            <textarea id="alamat_rumah" name="alamat_rumah" rows="3"
                                placeholder="Jalan, Desa/Kelurahan, Kecamatan"
                                class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">{{ old('alamat_rumah') }}</textarea>
                        </div>

                        {{-- Simpanan Awal --}}
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">Simpanan Awal</label>

                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <span class="w-36 text-sm text-gray-600">Simpanan Pokok</span>
                                    <input type="number" name="simpanan_pokok" value="{{ old('simpanan_pokok', 50000) }}"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-1 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                                </div>

                                <!-- Simpanan Wajib Field -->
                                <div>
                                    <label for="simpanan_wajib" class="flex items-center gap-3 font-semibold text-gray-700">
                                        <span
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-yellow-50 text-yellow-600 shadow">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                                <circle cx="12" cy="9" r="4" />
                                                <rect x="6" y="14" width="12" height="6" rx="1.5" fill="currentColor"
                                                    opacity="0.08" />
                                            </svg>
                                        </span>
                                        <span>Simpanan Wajib</span>
                                    </label>
                                    <div class="mt-2 flex rounded-lg shadow-sm">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500">Rp</span>
                                        <input type="number" id="simpanan_wajib" name="simpanan_wajib" value="0" readonly
                                            class="block w-full border border-gray-200 rounded-r-lg px-3 py-2 bg-gray-100 cursor-not-allowed focus:outline-none">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Simpanan wajib bulan pertama belum dipotong, nominal akan dihitung mulai bulan
                                        berikutnya.
                                    </p>
                                </div>


                                {{-- Simpanan Sukarela --}}
                                <div class="flex items-center">
                                    <span class="w-36 text-sm text-gray-600">Simpanan Sukarela</span>
                                    <input type="number" name="simpanan_sukarela_awal"
                                        value="{{ old('simpanan_sukarela_awal') }}" placeholder="Opsional"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-1 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Nominal simpanan sukarela akan disesuaikan setelah disetujui pengurus keuangan.
                                </p>

                                {{-- Pinjaman --}}
                                <div class="flex items-center mt-3">
                                    <span class="w-36 text-sm text-gray-600">Pinjaman</span>
                                    <input type="number" name="pinjaman_awal" value="{{ old('pinjaman_awal') }}"
                                        placeholder="Opsional"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-1 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Nominal pinjaman akan berlaku setelah pengurus keuangan menyetujui.
                                </p>

                            </div>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end gap-3 mt-8">
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow transition">
                            Simpan
                        </button>
                        <a href="{{ route('pengurus.anggota.index') }}"
                            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg shadow transition">
                            Batal
                        </a>
                    </div>
            </form>
        </div>
    </div>
@endsection