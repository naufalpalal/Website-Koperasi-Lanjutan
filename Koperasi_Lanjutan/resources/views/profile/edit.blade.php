@php
    if (Auth::guard('pengurus')->check()) {
        $layout = 'Pengurus.index';
    } elseif (Auth::guard('web')->check()) {
        $layout = 'User.index';
    } else {
        $layout = 'layouts.app'; // fallback kalau tidak login
    }
@endphp


@extends($layout)


@section('title', 'Profil')

@section('content')
<!-- <div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Profile</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Kelola informasi profil dan keamanan akun Anda
        </p>
    </div> -->

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 md:p-8">
        <form action="{{ route('profile.update-combined') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- Informasi Profil Section --}}
            <div class="pb-8 border-b border-gray-200 dark:border-gray-700">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Update Password Section --}}
            <div class="pb-8">
                @include('profile.partials.update-password-form')
            </div>

            {{-- Submit Button & Success Message --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
                    <p x-data="{ show: true }" 
                       x-show="show" 
                       x-transition
                       x-init="setTimeout(() => show = false, 3000)"
                       class="text-sm text-green-600 dark:text-green-400">
                        ✓ Perubahan berhasil disimpan
                    </p>
                @else
                    <div></div>
                @endif

                <button type="submit" 
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg 
                           transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                           dark:focus:ring-offset-gray-800">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('js/profile-preview.js') }}"></script>
@endsection