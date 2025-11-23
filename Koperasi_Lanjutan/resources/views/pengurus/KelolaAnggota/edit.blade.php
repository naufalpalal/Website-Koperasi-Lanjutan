@extends('pengurus.index')

@section('title', 'Edit Anggota')

@section('content')
    {{-- Use Tailwind utilities instead of custom CSS --}}

    <div class="max-w-4xl mx-auto mt-8 px-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-lg p-8"> <!-- Tailwind card -->

            {{-- Header --}}
            <div class="flex justify-between items-center gap-4 border-b pb-4 mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-50 to-cyan-50 text-indigo-700 px-3 py-1 rounded-md font-semibold shadow-sm">
                        <!-- stronger user icon -->
                        <svg class="w-5 h-5 text-indigo-600" viewBox="0 0 24 24" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 12a4 4 0 100-8 4 4 0 000 8z" />
                            <path d="M4 20a8 8 0 0116 0v1H4v-1z" opacity="0.9" />
                        </svg>
                        <span>Edit Anggota</span>
                    </div>
                </div>
                <a href="{{ route('pengurus.anggota.index') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow text-sm transition">
                    <!-- back icon -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>

            {{-- Form --}}
            <form action="{{ route('pengurus.anggota.update', $anggota->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:divide-x md:divide-gray-100">
                    <!-- Tailwind gap + divider -->
                    {{-- Kolom Kiri --}}
                    <div class="md:px-6">
                        <div class="mb-6">
                            <label for="nama" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-indigo-50 text-indigo-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 12a4 4 0 100-8 4 4 0 000 8z" />
                                        <path d="M4 20a8 8 0 0116 0v1H4v-1z" opacity="0.9" />
                                    </svg>
                                </span>
                                <span>Nama <span class="text-red-500 ml-1">*</span></span>
                            </label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama', $anggota->nama) }}" class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none 
                                                                          @error('nama') border-red-500 @enderror"
                                placeholder="Nama lengkap">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="no_telepon" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-indigo-50 text-indigo-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21 16.92V20a2 2 0 01-2.18 2 19.86 19.86 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.86 19.86 0 012 5.18 2 2 0 014 3h3.09a2 2 0 012 .84l1.2 2.13a2 2 0 01-.45 2.3L8.91 11.09a12.07 12.07 0 005 5l1.78-1.03a2 2 0 012.3-.45l2.13 1.2a2 2 0 01.84 2V20z" />
                                    </svg>
                                </span>
                                <span>No Telepon <span class="text-red-500 ml-1">*</span></span>
                            </label>
                            <input type="tel" id="no_telepon" name="no_telepon"
                                value="{{ old('no_telepon', $anggota->no_telepon) }}" inputmode="tel" class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none 
                                                                          @error('no_telepon') border-red-500 @enderror"
                                placeholder="08xx-xxxx-xxxx" />
                            @error('no_telepon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="simpanan_pokok" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-yellow-50 text-yellow-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="9" r="4" />
                                        <rect x="6" y="14" width="12" height="6" rx="1.5" fill="currentColor"
                                            opacity="0.08" />
                                    </svg>
                                </span>
                                <span>Simpanan Pokok</span>
                            </label>
                            <input type="number" id="simpanan_pokok" name="simpanan_pokok"
                               value="{{ old('simpanan_pokok', $simpananPokok->nilai ?? 0) }}" min="0" step="1000"
                                inputmode="numeric"
                                class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none" />
                        </div>

                        <div class="mb-6">
                            <label for="simpanan_wajib" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span>Simpanan Wajib</span>
                            </label>
                            <input type="number" readonly id="simpanan_wajib" name="simpanan_wajib"
                                value="{{ old('simpanan_wajib', $totalSimpananWajib ?? 0) }}" min="0" step="1000"
                                inputmode="numeric"
                                class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none" />
                        </div>

                        <div class="mb-6">
                            <label for="simpanan_sukarela" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-yellow-50 text-yellow-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="12" cy="12" r="9" /><text x="12" y="15" font-size="8"
                                            text-anchor="middle" fill="#fff">Rp</text>
                                    </svg>
                                </span>
                                <span>Simpanan Sukarela (Terakhir)</span>
                            </label>
                            <input type="number" readonly id="simpanan_sukarela" name="simpanan_sukarela"
                                value="{{ old('simpanan_sukarela', $totalSimpananSukarela ?? 0) }}" min="0" step="1000"
                                inputmode="numeric"
                                class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none" />
                            <p class="text-xs text-gray-500 mt-1">Masukkan nilai tanpa tanda titik/koma. Contoh: 50000</p>
                        </div>

                        <div class="mb-6">
                            <label for="nip" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-indigo-50 text-indigo-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <rect x="3" y="4" width="18" height="14" rx="2" />
                                        <circle cx="8" cy="10" r="2" fill="#fff" />
                                    </svg>
                                </span>
                                <span>NIP</span>
                            </label>
                            <input type="text" id="nip" name="nip" value="{{ old('nip', $anggota->nip) }}" class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none 
                                                                          @error('nip') border-red-500 @enderror"
                                placeholder="NIP / NIK">
                            @error('nip')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="md:px-6">
                        <div class="mb-6">
                            <label for="tempat_lahir" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-indigo-50 text-indigo-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" />
                                        <circle cx="12" cy="9" r="2.2" fill="#fff" />
                                    </svg>
                                </span>
                                <span>Tempat Lahir</span>
                            </label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir"
                                value="{{ old('tempat_lahir', $anggota->tempat_lahir) }}" class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none 
                                                                          @error('tempat_lahir') border-red-500 @enderror"
                                placeholder="Kota / Kabupaten">
                            @error('tempat_lahir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="tanggal_lahir" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-indigo-50 text-indigo-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <rect x="3" y="4" width="18" height="18" rx="2" />
                                        <path d="M7 10h10M7 14h6" stroke="#fff" stroke-width="1.2" stroke-linecap="round" />
                                    </svg>
                                </span>
                                <span>Tanggal Lahir</span>
                            </label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $anggota->tanggal_lahir) }}"
                                class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none 
                                                                          @error('tanggal_lahir') border-red-500 @enderror">
                            @error('tanggal_lahir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="alamat_rumah" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-green-50 text-green-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M3 11l9-7 9 7v8a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-8z" />
                                    </svg>
                                </span>
                                <span>Alamat Rumah</span>
                            </label>
                            <textarea id="alamat_rumah" name="alamat_rumah" rows="3"
                                class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none 
                                                                             @error('alamat_rumah') border-red-500 @enderror"
                                placeholder="Alamat lengkap">{{ old('alamat_rumah', $anggota->alamat_rumah) }}</textarea>
                            @error('alamat_rumah')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label for="unit_kerja" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-indigo-50 text-indigo-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M3 7h18v4H3z" />
                                        <rect x="3" y="11" width="18" height="8" rx="1" fill="currentColor"
                                            opacity="0.06" />
                                    </svg>
                                </span>
                                <span>Unit Kerja</span>
                            </label>
                            <textarea id="unit_kerja" name="unit_kerja" rows="2" class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none 
                                                                             @error('unit_kerja') border-red-500 @enderror"
                                placeholder="Unit / Bagian">{{ old('unit_kerja', $anggota->unit_kerja) }}</textarea>
                            @error('unit_kerja')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Pinjaman --}}
                        <div class="mb-6">
                            <label for="total_pinjaman" class="flex items-center gap-3 font-semibold text-gray-700">
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-50 text-red-600 shadow">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 1a11 11 0 100 22 11 11 0 000-22zm1 16h-2v-2h2v2zm0-4h-2V5h2v8z" />
                                    </svg>
                                </span>
                                <span>Total Pinjaman</span>
                            </label>
                            <input type="number" readonly id="total_pinjaman" name="total_pinjaman"
                                value="{{ old('total_pinjaman', $totalPinjaman ?? 0) }}" min="0" step="1000"
                                inputmode="numeric"
                                class="mt-2 block w-full border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none" />
                        </div>
                    </div>
                </div>
                {{-- Dokumen Anggota --}}
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="flex items-center gap-3 mb-6">
                        <span
                            class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-purple-50 text-purple-600 shadow">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                                <path d="M14 2v6h6" fill="#fff" />
                                <path d="M8 13h8M8 17h5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </span>
                        <h3 class="text-lg font-bold text-gray-800">Dokumen Anggota</h3>
                    </div>

                    @if(isset($anggota->dokumen))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Dokumen Pendaftaran --}}
                            @if($anggota->dokumen->dokumen_pendaftaran)
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-purple-300 transition">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-purple-100 text-purple-600">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                                                <path d="M14 2v6h6M10 12h4M10 16h2" stroke="#fff" stroke-width="1.5" />
                                            </svg>
                                        </span>
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm">Dokumen Pendaftaran</p>
                                            <p class="text-xs text-gray-500">{{ basename($anggota->dokumen->dokumen_pendaftaran) }}
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ url('/pengurus/dokumen/lihat/' . $anggota->id . '/pendaftaran') }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 text-sm font-medium transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat
                                    </a>
                                </div>
                            @endif

                            {{-- SK Tenaga Kerja --}}
                            @if($anggota->dokumen->sk_tenaga_kerja)
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-purple-300 transition">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 text-blue-600">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z" />
                                                <path d="M14 2v6h6M10 12h4M10 16h2" stroke="#fff" stroke-width="1.5" />
                                            </svg>
                                        </span>
                                        <div>
                                            <p class="font-semibold text-gray-800 text-sm">SK Tenaga Kerja</p>
                                            <p class="text-xs text-gray-500">{{ basename($anggota->dokumen->sk_tenaga_kerja) }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ url('/pengurus/dokumen/lihat/' . $anggota->id . '/sk') }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 text-sm font-medium transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-500 font-medium">Belum ada dokumen yang diupload</p>
                            <p class="text-gray-400 text-sm mt-1">Anggota dapat mengupload dokumen melalui portal anggota</p>
                        </div>
                    @endif
                </div>
                {{-- Tombol Aksi --}}
                <div class="flex justify-end gap-3 mt-8"> <!-- tambah margin top -->
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow transition">
                        Update
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