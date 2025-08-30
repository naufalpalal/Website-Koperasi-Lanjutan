<x-guest-layout>
    <h2 class="text-3xl sm:text-4xl font-normal text-center text-gray-800 mb-2">Login</h2>
    <p class="text-center text-gray-500 mb-6 text-base sm:text-lg">To Continue Your Account</p>
    <form method="POST" action="{{ route('login_submit') }}" class="space-y-6">
        @csrf
        <!-- Email -->
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-700 text-xl">
                <i class="fa-solid fa-id-card"></i>
            </span>
            <input type="text" name="nip" value="{{ old('nip') }}" required autofocus
                class="w-full pl-12 pr-3 py-3 rounded-lg border border-gray-400 bg-blue-50 text-lg focus:ring-0 focus:outline-none"
                placeholder="NIP">
        </div>
        <!-- Password -->
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-700 text-xl">
                <i class="fa-solid fa-lock"></i>
            </span>
            <input type="password" name="password" required
                class="w-full pl-12 pr-3 py-3 rounded-lg border border-gray-400 bg-blue-50 text-lg focus:ring-0 focus:outline-none"
                placeholder="Password">
        </div>
        <!-- Forgot Password -->
        <div class="flex justify-end">
            <a href="{{ route('password.request') }}" class="text-base text-blue-500 hover:underline">
                Forgot password?
            </a>
        </div>
        <!-- Submit -->
        <button type="submit"
            class="w-full bg-blue-500 text-white text-2xl sm:text-3xl font-normal py-2 rounded-lg mt-2 border border-blue-500 hover:bg-blue-600 transition">
            Masuk
        </button>
    </form>
</x-guest-layout>
