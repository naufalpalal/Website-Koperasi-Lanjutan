@extends('Layouts.app')

@section('layout')

    {{-- Sidebar Pengurus --}}
    @include('pengurus.layouts.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 px-8 py-6 md:ml-64">
        <div class="p-6">
            @yield('content')
        </div>
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
