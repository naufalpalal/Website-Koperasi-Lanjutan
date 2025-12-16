@extends('Layouts.app')

@section('layout')


    {{-- Sidebar --}}
    @include('pengurus.layouts.sidebar')

    {{-- Main content --}}
    <main class="flex md:ml-64 p-2 sm:p-4">
        @yield('content')
    </main>

    {{-- JS khusus pengurus --}}
    <script src="{{ asset('assets/js/simpanan.js') }}"></script>
    <script src="{{ asset('assets/js/checklist.js') }}"></script>
    <script src="{{ asset('assets/js/register.js') }}"></script>
    <script src="{{ asset('assets/js/reviewdoc.js') }}"></script>
    <script src="{{ asset('assets/js/dataanggota.js') }}"></script>
    <script src="{{ asset('assets/js/simpananwajib_listnomor.js') }}"></script>
    <script src="{{ asset('assets/js/simpananwajib_search.js') }}"></script>
    <script src="{{ asset('assets/js/bendahara-setting.js') }}"></script>

    @yield('scripts')

@endsection