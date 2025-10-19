<x-guest-layout>
    <div class="relative w-full max-w-xl flex flex-col items-center justify-center min-h-screen px-4">
        <!-- Logo di luar card -->
        <img src="{{ asset('assets/favicon.png') }}" alt="Logo" class="w-20 h-20 mb-6">

        <div class="bg-white shadow-md rounded-2xl p-8 w-full max-w-md">
            <h2 class="text-3xl sm:text-4xl font-semibold text-center text-gray-800 mb-2">
                Atur Ulang Password
            </h2>
            <p class="text-center text-gray-500 mb-6 text-base sm:text-lg">
                Masukkan password baru untuk akun Anda.
            </p>

            <!-- Form Reset Password -->
            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <!-- Token dari URL -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Input Email -->
                <div class="relative mb-4">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-700 text-xl">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                        class="w-full pl-12 pr-3 py-3 rounded-lg border 
                        @error('email') border-red-500 @else border-gray-400 @enderror 
                        bg-blue-50 text-lg focus:ring-0 focus:outline-none"
                        placeholder="Email">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Password Baru -->
                <div class="relative mb-4">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-700 text-xl">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input id="password" type="password" name="password" required
                        class="w-full pl-12 pr-3 py-3 rounded-lg border 
                        @error('password') border-red-500 @else border-gray-400 @enderror 
                        bg-blue-50 text-lg focus:ring-0 focus:outline-none"
                        placeholder="Password Baru">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="relative mb-4">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-700 text-xl">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full pl-12 pr-3 py-3 rounded-lg border border-gray-400 
                        bg-blue-50 text-lg focus:ring-0 focus:outline-none"
                        placeholder="Konfirmasi Password Baru">
                </div>

                <!-- Tombol Simpan -->
                <button type="submit"
                    class="w-full bg-blue-500 text-white text-2xl sm:text-3xl font-normal py-2 rounded-lg mt-2 border border-blue-500 hover:bg-blue-600 transition">
                    Simpan Password Baru
                </button>

                <!-- Link kembali -->
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-base text-blue-500 hover:underline">
                        Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
