<x-guest-layout :isRegister="false">
    <h2 class="text-3xl sm:text-4xl font-normal text-center text-gray-900 mb-2">
        Login Pengurus
    </h2>
    <p class="text-center text-gray-600 mb-6 text-base sm:text-lg">
        Masuk ke akun pengurus koperasi
    </p>

    <form method="POST" action="{{ route('pengurus.login') }}" class="space-y-6">
        @csrf

        <!-- NIP -->
        <div class="relative mb-4">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-xl">
                <i class="fa-solid fa-id-card"></i>
            </span>
            <input 
                type="text" 
                name="nip" 
                value="{{ old('nip') }}" 
                required 
                autofocus
                class="w-full pl-12 pr-3 py-3 rounded-lg border 
                       @error('nip') border-red-500 @else border-gray-300 @enderror 
                       bg-white text-black placeholder-gray-500 
                       text-lg focus:outline-none focus:border-blue-500 transition"
                placeholder="NIP Pengurus">
            @error('nip')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="relative mb-4">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-xl">
                <i class="fa-solid fa-lock"></i>
            </span>
            <input 
                type="password" 
                name="password" 
                required
                class="w-full pl-12 pr-3 py-3 rounded-lg border 
                       @error('password') border-red-500 @else border-gray-300 @enderror 
                       bg-white text-black placeholder-gray-500 
                       text-lg focus:outline-none focus:border-blue-500 transition"
                placeholder="Password Pengurus">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Forgot Password -->
        <div class="flex justify-end text-sm">
            <a href="{{ route('password.request') }}" class="text-blue-500 hover:underline">
                Lupa password?
            </a>
        </div>

        <!-- Submit -->
        <button 
            type="submit"
            class="w-full bg-blue-500 text-white text-2xl sm:text-3xl font-normal py-2 rounded-lg mt-2 
                   border border-blue-500 hover:bg-blue-600 transition">
            Masuk sebagai Pengurus
        </button>

        <!-- Link ke Login Anggota -->
        <p class="text-center text-gray-600 text-sm mt-4">
            Bukan pengurus? 
            <a href="{{ route('login') }}" class="text-blue-500 hover:underline">
                Login Anggota
            </a>
        </p>
    </form>
</x-guest-layout>
