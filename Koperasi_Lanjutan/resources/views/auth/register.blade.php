<x-guest-layout :isRegister="true">
    <div class="max-w-6xl mx-auto mt-6 px-4">

        <div class="bg-white rounded-xl shadow p-6">
            {{-- Header --}}
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h5 class="text-xl font-semibold text-gray-700">Formulir Pendaftaran Anggota Koperasi</h5>
                <a href="{{ route('login') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow text-sm transition">
                    Kembali ke Login
                </a>
            </div>

            {{-- Error --}}
            @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                {{-- Pilih Jenis Anggota --}}
                <div class="mb-6">
                    <label for="jenis_anggota" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Anggota <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis_anggota" name="jenis_anggota" required
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        <option value="">-- Pilih Jenis Anggota --</option>
                        <option value="pegawai">Pegawai poliwangi</option>
                        <option value="non_pegawai">Non-Pegawai</option>
                    </select>
                </div>

                {{-- Grid form hanya tampil setelah pilih jenis anggota --}}
                <div id="form_fields" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
                    {{-- Kolom Kiri --}}
                    <div>
                        {{-- Nama --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                        </div>

                        {{-- NIP / Username --}}
                        <div class="mb-4" id="field_nip_username" style="display: none;">
                            <label for="nip_username" class="block text-sm font-medium text-gray-700" id="label_nip_username">NIP</label>
                            <input type="text" id="nip_username" name="nip_username" value="{{ old('nip_username') }}"
                                class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>

                        {{-- No HP --}}
                        <div class="mb-4">
                            <label for="no_telepon" class="block text-sm font-medium text-gray-700">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}"
                                class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                        </div>

                        {{-- Tombol Kirim OTP --}}
                        <div class="mb-4">
                            <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition">
                                Kirim OTP
                            </button>
                        </div>

                        {{-- Kode OTP --}}
                        <div class="mb-4 flex items-center gap-2">
                            <input type="text" id="kode_otp" name="kode_otp" placeholder="Kode OTP"
                                class="flex-1 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>

                        {{-- Tempat dan Tanggal Lahir --}}
                        <div class="mb-4">
                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div>
                        {{-- Alamat Rumah --}}
                        <div class="mb-4">
                            <label for="alamat_rumah" class="block text-sm font-medium text-gray-700">Alamat Rumah</label>
                            <textarea id="alamat_rumah" name="alamat_rumah" rows="3"
                                class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">{{ old('alamat_rumah') }}</textarea>
                        </div>

                        {{-- Unit Kerja --}}
                        <div class="mb-4">
                            <label for="unit_kerja" class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                            <input type="text" id="unit_kerja" name="unit_kerja" value="{{ old('unit_kerja') }}"
                                class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>

                        {{-- Password --}}
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" id="password" name="password" required
                                class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        </div>
                    </div>
                </div>


                {{-- Tombol Aksi --}}
                <div class="flex justify-end gap-3 mt-6">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg shadow transition">
                        Daftar
                    </button>
                    <a href="{{ route('login') }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-lg shadow transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-guest-layout>