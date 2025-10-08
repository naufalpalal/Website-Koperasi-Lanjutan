{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Lupa password? Tidak masalah. Masukkan nomor telepon Anda dan kami akan mengirimkan link atau kode untuk reset password.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- <form method="POST" action="{{ route('password.phone') }}"> --}}
        {{-- @csrf --}}

        <!-- Nomor Telepon -->
        {{-- <div>
            <x-input-label for="phone" :value="__('Nomor Telepon')" />
            <x-text-input id="phone" class="block mt-1 w-full border border-gray-400 rounded-md p-2 bg-white text-gray-900" type="text" name="phone" :value="old('phone')" required autofocus placeholder="contoh: 081234567890"/>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Kirim Link Reset Password via Nomor Telepon') }}
            </x-primary-button>
        </div>
    {{-- </form> --}}


{{-- </x-guest-layout> --}}
