<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-blue-500">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-10 flex flex-col items-center">
            <!-- Logo -->
            <a href="/" class="mb-6">
                <x-application-logo class="w-16 h-16 fill-current text-blue-500" />
            </a>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <h2 class="text-3xl font-bold text-center text-blue-600 mb-8">Login</h2>

            <form method="POST" action="{{ route('login_submit') }}" class="w-full">
                @csrf

                <!-- Email Address -->
                <div class="mb-6">
                    <x-input-label for="nip" :value="__('NIP')" class="text-blue-700 font-semibold" />
                    <x-text-input id="nip" class="block mt-2 w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 px-4 py-2" type="text" name="nip" :value="old('nip')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('nip')" class="mt-2 text-red-500" />
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <x-input-label for="password" :value="__('Password')" class="text-blue-700 font-semibold" />
                    <x-text-input id="password" class="block mt-2 w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500 px-4 py-2"
                        type="password"
                        name="password"
                        required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mb-6">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" name="remember">
                    <label for="remember_me" class="ml-2 text-sm text-gray-700">{{ __('Remember me') }}</label>
                </div>

                <div class="flex items-center justify-between mb-8">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-500 hover:underline" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <x-primary-button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    {{ __('Log in') }}
                </x-primary-button>
            </form>
        </div>
    </div>
</x-guest-layout>
