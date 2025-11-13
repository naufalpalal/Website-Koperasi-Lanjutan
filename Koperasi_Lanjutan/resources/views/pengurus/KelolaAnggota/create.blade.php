@extends('pengurus.index')

@section('title', 'Tambah Anggota')

@section('content')
    <div class="max-w-4xl mx-auto mt-8 px-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-lg p-8">
            {{-- Header --}}
            <div class="flex justify-between items-center gap-4 border-b pb-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-50 to-cyan-50 text-indigo-700 px-3 py-1 rounded-md font-semibold shadow-sm">
                        <svg class="w-5 h-5 text-indigo-600" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12a4 4 0 100-8 4 4 0 000 8z" />
                            <path d="M4 20a8 8 0 0116 0v1H4v-1z" opacity="0.9"/>
                        </svg>
                        <span>Tambah Anggota</span>
                    </div>
                </div>
                <a href="{{ route('pengurus.anggota.index') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow text-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>
            </div>

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
                        <!-- Nama Field -->
                        <div>
                            <label for="nama" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-indigo-50 text-indigo-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                                        <path d="M4 20a8 8 0 0116 0v1H4v-1z" opacity="0.9"/>
                                    </svg>
                                </span>
                                <span>Nama <span class="text-red-500 ml-1">*</span></span>
                            </label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" 
                                   class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        </div>

                        <!-- No Telepon Field -->
                        <div>
                            <label for="no_telepon" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-indigo-50 text-indigo-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M21 16.92V20a2 2 0 01-2.18 2 19.86 19.86 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.86 19.86 0 012 5.18 2 2 0 014 3h3.09a2 2 0 012 .84l1.2 2.13a2 2 0 01-.45 2.3L8.91 11.09a12.07 12.07 0 005 5l1.78-1.03a2 2 0 012.3-.45l2.13 1.2a2 2 0 01.84 2V20z"/>
                                    </svg>
                                </span>
                                <span>No Telepon <span class="text-red-500 ml-1">*</span></span>
                            </label>
                            <input type="tel" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}"
                                   class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        </div>

                        <!-- Simpanan Pokok Field -->
                        <div>
                            <label for="simpanan_pokok" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-yellow-50 text-yellow-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="12" cy="9" r="4"/>
                                        <rect x="6" y="14" width="12" height="6" rx="1.5" fill="currentColor" opacity="0.08"/>
                                    </svg>
                                </span>
                                <span>Simpanan Pokok</span>
                            </label>
                            <div class="mt-2 flex rounded-lg shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500">Rp</span>
                                <input type="number" id="simpanan_pokok" name="simpanan_pokok" value="{{ old('simpanan_pokok', 50000) }}"
                                       class="block w-full border border-gray-200 rounded-r-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                        </div>

                        <!-- Simpanan Wajib Field -->
                        <div>
                            <label for="simpanan_wajib" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-yellow-50 text-yellow-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="12" cy="9" r="4"/>
                                        <rect x="6" y="14" width="12" height="6" rx="1.5" fill="currentColor" opacity="0.08"/>
                                    </svg>
                                </span>
                                <span>Simpanan Wajib</span>
                            </label>
                            <div class="mt-2 flex rounded-lg shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500">Rp</span>
                                <input type="number" id="simpanan_wajib" name="simpanan_wajib" value="{{ old('simpanan_wajib', 40000) }}"
                                       class="block w-full border border-gray-200 rounded-r-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Masukkan nominal simpanan wajib per bulan.</p>
                        </div>

                        <!-- Simpanan Sukarela Field -->
                        <div>
                            <label for="simpanan_sukarela_awal" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-yellow-50 text-yellow-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 3a9 9 0 100 18 9 9 0 000-18z"/>
                                        <text x="12" y="16" font-size="8" text-anchor="middle" fill="#fff" font-family="Arial" font-weight="700">Rp</text>
                                    </svg>
                                </span>
                                <span>Simpanan Sukarela Awal</span>
                            </label>
                            <div class="mt-2 flex rounded-lg shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-200 bg-gray-50 text-gray-500">Rp</span>
                                <input type="number" id="simpanan_sukarela_awal" name="simpanan_sukarela_awal" value="{{ old('simpanan_sukarela_awal') }}"
                                       class="block w-full border border-gray-200 rounded-r-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Opsional: saldo awal simpanan sukarela.</p>
                        </div>

                        <!-- NIP Field -->
                        <div>
                            <label for="nip" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-indigo-50 text-indigo-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <rect x="3" y="4" width="18" height="14" rx="2"/>
                                        <circle cx="8" cy="10" r="2" fill="#fff"/>
                                    </svg>
                                </span>
                                <span>NIP</span>
                            </label>
                            <input type="text" id="nip" name="nip" value="{{ old('nip') }}" 
                                   class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none" 
                                   placeholder="NIP">
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="md:px-6 space-y-6">
                        <!-- Tempat Lahir Field -->
                        <div>
                            <label for="tempat_lahir" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-indigo-50 text-indigo-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                                        <circle cx="12" cy="9" r="2.2" fill="#fff"/>
                                    </svg>
                                </span>
                                <span>Tempat Lahir</span>
                            </label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                   class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                   placeholder="Contoh: Banyuwangi">
                        </div>

                        <!-- Tanggal Lahir Field -->
                        <div>
                            <label for="tanggal_lahir" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-indigo-50 text-indigo-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                                        <path d="M7 10h10M7 14h6" stroke="#fff" stroke-width="1.2" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <span>Tanggal Lahir</span>
                            </label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                   class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        </div>

                        <!-- Alamat Rumah Field -->
                        <div>
                            <label for="alamat_rumah" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-green-50 text-green-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M3 11l9-7 9 7v8a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-8z"/>
                                    </svg>
                                </span>
                                <span>Alamat Rumah</span>
                            </label>
                            <textarea id="alamat_rumah" name="alamat_rumah" rows="3"
                                    class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                    placeholder="Jalan, Desa/Kelurahan, Kecamatan">{{ old('alamat_rumah') }}</textarea>
                        </div>

                        <!-- Unit Kerja Field -->
                        <div>
                            <label for="unit_kerja" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-indigo-50 text-indigo-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M3 7h18v4H3z"/>
                                        <rect x="3" y="11" width="18" height="8" rx="1" fill="currentColor" opacity="0.06"/>
                                    </svg>
                                </span>
                                <span>Unit Kerja</span>
                            </label>
                            <input type="text" id="unit_kerja" name="unit_kerja" value="{{ old('unit_kerja') }}"
                                   class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                   placeholder="Unit / Bagian">
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
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