{{-- Hapus tag <section> dan <form>, karena sudah ada di parent --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Informasi Profil
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Perbarui informasi profil Anda di bawah ini.
            </p>
        </div>

        {{-- Foto Profil --}}
        <div class="mb-6">
            <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                Foto Profil
            </label>
            <div class="mt-2 flex items-center">
                <span class="inline-block h-20 w-20 rounded-full overflow-hidden bg-gray-100">
                    <img id="photoPreview" src="{{ $user->photo_path ? asset('storage/' . $user->photo_path) : '' }}"
                        data-original="{{ $user->photo_path ? asset('storage/' . $user->photo_path) : '' }}"
                        alt="Profile Photo" class="h-full w-full object-cover">
                    <svg id="defaultAvatar" class="{{ $user->photo_path ? 'hidden' : '' }} h-full w-full text-gray-300"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 24H0V0h24v24z" fill="none" />
                        <path
                            d="M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4 8 5.79 8 8s1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                </span>
                <input type="file" name="photo" id="photo" accept="image/*"
                    class="ml-5 text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            </div>
            <p class="text-xs text-gray-500 mt-1">Pilih foto profil baru (maksimal 2MB, format: jpg/png).</p>
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nama Lengkap --}}
                <div class="md:col-span-2">
                    <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                        Lengkap</label>
                    <x-text-input id="nama" name="nama" type="text"
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Masukkan nama lengkap" value="{{ old('nama', $user->nama) }}" required autofocus
                        autocomplete="nama" aria-invalid="{{ $errors->has('nama') ? 'true' : 'false' }}" />
                    <p class="text-xs text-gray-500 mt-1">Isi sesuai dengan identitas resmi Anda.</p>
                    <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                </div>

                {{-- NIP --}}
                <div>
                    <label for="nip" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        NIP
                    </label>
                    <x-text-input id="nip" name="nip" type="text"
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Masukkan NIP Anda" value="{{ old('nip', $user->nip) }}" autocomplete="off" />
                    <x-input-error class="mt-2" :messages="$errors->get('nip')" />
                </div>

                {{-- Nomor Telepon --}}
                <div>
                    <label for="no_telepon" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nomor Telepon
                    </label>
                    <x-text-input id="no_telepon" name="no_telepon" type="text"
                        class="mt-1 block w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Contoh: 081234567890" value="{{ old('no_telepon', $user->no_telepon) }}"
                        autocomplete="tel" aria-invalid="{{ $errors->has('no_telepon') ? 'true' : 'false' }}" />
                    <p class="text-xs text-gray-500 mt-1">Gunakan format tanpa spasi atau tanda (contoh: 081234567890).
                    </p>
                    <x-input-error class="mt-2" :messages="$errors->get('no_telepon')" />
                </div>
            </div>

            {{-- Alamat Rumah --}}
            <div>
                <label for="alamat_rumah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat
                    Rumah</label>
                <textarea id="alamat_rumah" name="alamat_rumah" rows="3"
                    class="mt-1 block w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Tuliskan alamat lengkap (jalan, desa, kecamatan, kota)">{{ old('alamat_rumah', $user->alamat_rumah) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Tuliskan alamat lengkap agar memudahkan verifikasi.</p>
                <x-input-error class="mt-2" :messages="$errors->get('alamat_rumah')" />
            </div>
        </div>