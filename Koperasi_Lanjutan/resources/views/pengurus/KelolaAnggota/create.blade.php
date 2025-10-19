@extends('pengurus.index')

@section('title', 'Tambah Anggota')

@section('content')
    <div class="max-w-4xl mx-auto mt-6">
        <div class="bg-white rounded-xl shadow p-6">
            {{-- Header --}}
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h5 class="text-xl font-semibold text-gray-700">Tambah Anggota</h5>
                <a href="{{ route('pengurus.KelolaAnggota.index') }}"
                    class="bg-blue-400 hover:bg-blue-500 text-white px-4 py-2 rounded-lg shadow text-sm transition">
                    Kembali
                </a>
            </div>
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('pengurus.KelolaAnggota.store') }}" method="POST" novalidate>
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Kolom Kiri --}}
                    <div class="space-y-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama <span class="text-red-500">*</span></label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="mt-2 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none 
                                          @error('nama') border-red-500 @enderror">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="no_telepon" class="block text-sm font-medium text-gray-700">No Telepon <span class="text-red-500">*</span></label>
                            <input type="tel" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" inputmode="numeric" pattern="[0-9]*" class="mt-2 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none 
                                          @error('no_telepon') border-red-500 @enderror" placeholder="081234567890">
                            @error('no_telepon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="simpanan_pokok" class="block text-sm font-medium text-gray-700">Simpanan Pokok</label>
                            <div class="mt-2 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">Rp</span>
                                <input type="number" id="simpanan_pokok" name="simpanan_pokok" value="{{ old('simpanan_pokok', 50000) }}" class="block w-full border border-gray-300 rounded-r-md px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" inputmode="numeric">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Nominal default simpanan pokok.</p>
                        </div>

                        <div>
                            <label for="simpanan_wajib" class="block text-sm font-medium text-gray-700">Simpanan Wajib</label>
                            <div class="mt-2 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">Rp</span>
                                <input type="number" id="simpanan_wajib" name="simpanan_wajib" value="{{ old('simpanan_wajib', 40000) }}" class="block w-full border border-gray-300 rounded-r-md px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" inputmode="numeric">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Masukkan nominal simpanan wajib per bulan.</p>
                        </div>

                        <div>
                            <label for="simpanan_sukarela_awal" class="block text-sm font-medium text-gray-700">Simpanan Sukarela Awal</label>
                            <div class="mt-2 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">Rp</span>
                                <input type="number" id="simpanan_sukarela_awal" name="simpanan_sukarela_awal" value="{{ old('simpanan_sukarela_awal') }}" class="block w-full border border-gray-300 rounded-r-md px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" inputmode="numeric">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Opsional: saldo awal simpanan sukarela.</p>
                        </div>

                        <div>
                            <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                            <input type="text" id="nip" name="nip" value="{{ old('nip') }}" class="mt-2 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none 
                                          @error('nip') border-red-500 @enderror" placeholder="NIP">
                            @error('nip')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="space-y-6">
                        <div>
                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                class="mt-2 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none 
                                          @error('tempat_lahir') border-red-500 @enderror" placeholder="Contoh: Banyuwangi">
                            @error('tempat_lahir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                class="mt-2 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none 
                                          @error('tanggal_lahir') border-red-500 @enderror">
                            @error('tanggal_lahir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="alamat_rumah" class="block text-sm font-medium text-gray-700">Alamat Rumah</label>
                            <textarea id="alamat_rumah" name="alamat_rumah" rows="3"
                                class="mt-2 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none 
                                             @error('alamat_rumah') border-red-500 @enderror" placeholder="Jalan, Desa/Kelurahan, Kecamatan">{{ old('alamat_rumah') }}</textarea>
                            @error('alamat_rumah')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="unit_kerja" class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                            <input id="unit_kerja" name="unit_kerja" rows="3"
                                class="mt-2 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none 
                                             @error('unit_kerja') border-red-500 @enderror" placeholder="Unit / Bagian">{{ old('unit_kerja') }}</input>
                            @error('unit_kerja')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end gap-3 mt-8">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow transition">
                        Simpan
                    </button>
                    <a href="{{ route('pengurus.KelolaAnggota.index') }}"
                        class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg shadow transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection