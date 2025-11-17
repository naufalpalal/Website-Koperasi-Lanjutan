<x-guest-layout :isRegister="false">
    <h2 class="text-2xl sm:text-3xl font-normal text-center text-gray-900 mb-2">Login</h2>
    <p class="text-center text-gray-600 mb-6 text-sm sm:text-base">Login Sebagai Anggota</p>
    <form method="POST" action="{{ route('login_submit') }}" class="space-y-6">
        @csrf

        <!-- NIP -->
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-lg">
                <i class="fa-solid fa-id-card"></i>
            </span>
            <input 
                type="text" 
                name="nip" 
                value="{{ old('nip') }}" 
                required 
                autofocus
                class="w-full pl-10 pr-3 py-2 rounded-lg border 
                       @error('nip') border-red-500 @else border-gray-300 @enderror 
                       bg-white text-black placeholder-gray-500 
                       text-lg focus:outline-none focus:border-blue-500 transition"
                placeholder="NIP">
            @error('nip')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-lg">
                <i class="fa-solid fa-lock"></i>
            </span>
            <input 
                type="password" 
                id="password"
                name="password" 
                required
                class="w-full pl-10 pr-3 py-2 rounded-lg border 
                       @error('password') border-red-500 @else border-gray-300 @enderror 
                       bg-white text-black placeholder-gray-500 
                       text-lg focus:outline-none focus:border-blue-500 transition"
                placeholder="Password">
            <button type="button" onclick="togglePassword('password', this)"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-eye"></i>
            </button>
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Forgot Password & Register -->
        <div class="flex justify-between text-sm">
            <a href="{{ route('password.request') }}" class="text-blue-500 hover:underline">Forgot password?</a>
            <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Register</a>
            <a href="{{ route('pengurus.login') }}" class="text-blue-500 hover:underline">LOGIN PENGURUS</a>
        </div>

        <!-- Submit -->
        <button 
            type="submit"
            class="w-full bg-blue-500 text-white text-base sm:text-lg font-medium py-2 rounded-lg 
                   border border-blue-500 hover:bg-blue-600 transition">
            Masuk
        </button>
    </form>

<script src="{{ asset('assets/js/show-password.js') }}"></script>
</x-guest-layout>
