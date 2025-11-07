{{-- Hapus tag <section> dan <form>, karena sudah ada di parent --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Ubah Password
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.
            </p>
        </div>

        <div class="space-y-6">
            <div class="grid grid-cols-1 gap-4">
                {{-- Current Password --}}
                <div>
                    <label for="current_password"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Saat Ini</label>
                    <div class="relative">
                        <x-text-input id="current_password" name="current_password" type="password"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Masukkan password saat ini" autocomplete="current-password" />
                        <button type="button" onclick="togglePassword('current_password', this)"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 focus:outline-none">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Masukkan kata sandi yang sedang Anda gunakan.</p>
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                {{-- New Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password
                        Baru</label>
                    <div class="relative">
                        <x-text-input id="password" name="password" type="password"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Minimal 8 karakter" autocomplete="new-password" />
                        <button type="button" onclick="togglePassword('password', this)"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 focus:outline-none">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Gunakan kombinasi huruf besar, kecil, angka, dan simbol untuk
                        keamanan lebih baik.</p>
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
                    <div class="relative">
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Ulangi password baru" autocomplete="new-password" />
                        <button type="button" onclick="togglePassword('password_confirmation', this)"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 focus:outline-none">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Pastikan kedua input password sama.</p>
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>
            </div>
        </div>