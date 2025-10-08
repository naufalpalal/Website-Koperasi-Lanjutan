<!-- <x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>@extends('pengurus.index')

@section('title', 'Registrasi Anggota')

@section('content')
<div class="max-w-4xl mx-auto mt-6">
    <div class="bg-white rounded-xl shadow p-6">
        {{-- Header --}}
        <div class="flex justify-between items-center border-b pb-4 mb-6">
            <h5 class="text-xl font-semibold text-gray-700">Registrasi Anggota Koperasi</h5>
            <a href="{{ route('login') }}" 
               class="bg-blue-400 hover:bg-blue-500 text-white px-4 py-2 rounded-lg shadow text-sm transition">
                Kembali ke Login
            </a>
        </div>

        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kolom Kiri --}}
                <div>
                    {{-- Nama --}}
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none
                               @error('name') border-red-500 @enderror" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- No Telepon --}}
                    <div class="mb-4">
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700">No Telepon <span class="text-red-500">*</span></label>
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}"
                               class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none
                               @error('no_telepon') border-red-500 @enderror" required>
                        @error('no_telepon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Simpanan Sukarela --}}
                    <div class="mb-3">
                        <label for="simpanan_sukarela_awal" class="block text-sm font-medium text-gray-700">Simpanan Sukarela Awal <span class="text-red-500">*</span></label>
                        <input type="number" id="simpanan_sukarela_awal" name="simpanan_sukarela_awal" value="{{ old('simpanan_sukarela_awal') }}"
                               required min="0"
                               class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    {{-- NIP --}}
                    <div class="mb-4">
                        <label for="nip" class="block text-sm font-medium text-gray-700">NIP</label>
                        <input type="text" id="nip" name="nip" value="{{ old('nip') }}"
                               class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none
                               @error('nip') border-red-500 @enderror">
                        @error('nip')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div>
                    {{-- Tempat Lahir --}}
                    <div class="mb-4">
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                               class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="mb-4">
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                               class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-4">
                        <label for="alamat_rumah" class="block text-sm font-medium text-gray-700">Alamat Rumah</label>
                        <textarea id="alamat_rumah" name="alamat_rumah" rows="3"
                                  class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">{{ old('alamat_rumah') }}</textarea>
                    </div>

                    {{-- Unit Kerja --}}
                    <div class="mb-4">
                        <label for="unit_kerja" class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                        <textarea id="unit_kerja" name="unit_kerja" rows="3"
                                  class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">{{ old('unit_kerja') }}</textarea>
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none" required>
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" required
                               class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3 mt-6">
                <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg shadow transition">
                    Daftar
                </button>
                <a href="{{ route('login') }}" 
                   class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg shadow transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection


        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>  -->
