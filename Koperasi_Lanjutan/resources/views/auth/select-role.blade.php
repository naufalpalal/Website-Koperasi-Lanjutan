@extends('layouts.app')

@section('title', 'Pilih Role')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 px-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">
        
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="mx-auto w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Pilih Role Anda</h2>
            <p class="text-gray-600">Selamat datang, <span class="font-semibold text-blue-600">{{ $user->nama }}</span></p>
            <p class="text-sm text-gray-500 mt-1">Anda memiliki akses ke beberapa role</p>
        </div>

        {{-- Role Options --}}
        <div class="space-y-4">
            
            {{-- Login sebagai Anggota --}}
            <form action="{{ route('login.select-role') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="anggota">
                <button type="submit" class="w-full group">
                    <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-blue-500 hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-500 transition-colors">
                                    <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <h3 class="font-bold text-gray-800 text-lg">Anggota</h3>
                                    <p class="text-sm text-gray-500">Dashboard Anggota Koperasi</p>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </button>
            </form>

            {{-- Login sebagai Pengurus --}}
            <form action="{{ route('login.select-role') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="pengurus">
                <button type="submit" class="w-full group">
                    <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-500 transition-colors">
                                    <svg class="w-6 h-6 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <h3 class="font-bold text-gray-800 text-lg">Pengurus</h3>
                                    <p class="text-sm text-gray-500">Dashboard Pengurus - {{ $pengurus->role ?? 'Staff' }}</p>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </button>
            </form>

        </div>

        {{-- Logout Button --}}
        <div class="mt-6 text-center">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 hover:underline">
                    Batalkan & Logout
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
