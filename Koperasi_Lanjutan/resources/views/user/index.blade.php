@extends('Layouts.app')

@section('layout')

    {{-- Sidebar --}}
    @include('user.layouts.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 md:ml-64 p-2 sm:p-4">
        @yield('content')
    </main>

@endsection
