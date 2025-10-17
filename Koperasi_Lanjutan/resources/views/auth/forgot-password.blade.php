<x-guest-layout>
        <!-- Card utama -->
        <div class="bg-gray-900 shadow-2xl rounded-3xl p-8 sm:p-10 max-w-md w-full text-center text-white">
            <h2 class="text-3xl font-semibold mb-2">Lupa Password</h2>
            <p class="text-gray-300 text-sm mb-6">
                Masukkan <span class="font-semibold">NIP</span> dan nomor <span class="font-semibold">WhatsApp</span> Anda untuk menerima kode OTP.
            </p>

            <!-- Form -->
            <form method="POST" action="{{ route('password.sendOtp') }}" class="space-y-5">
                @csrf

                <!-- Input NIP -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-lg">
                        <i class="fa-solid fa-id-card"></i>
                    </span>
                    <input id="nip" type="text" name="nip" value="{{ old('nip') }}" required
                        class="w-full pl-12 pr-3 py-3 rounded-lg border border-gray-600 bg-gray-800 text-white placeholder-gray-400 focus:border-blue-400 focus:ring-0"
                        placeholder="Masukkan NIP">
                    @error('nip')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Nomor WhatsApp -->
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-lg">
                        <i class="fa-brands fa-whatsapp"></i>
                    </span>
                    <input id="whatsapp" type="text" name="whatsapp" value="{{ old('whatsapp') }}" required
                        class="w-full pl-12 pr-3 py-3 rounded-lg border border-gray-600 bg-gray-800 text-white placeholder-gray-400 focus:border-blue-400 focus:ring-0"
                        placeholder="Nomor WhatsApp aktif">
                    @error('whatsapp')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol -->
                <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white text-lg font-medium py-3 rounded-lg transition">
                    Kirim Kode OTP ke WhatsApp
                </button>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-blue-300 hover:underline text-sm">
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
</x-guest-layout>
