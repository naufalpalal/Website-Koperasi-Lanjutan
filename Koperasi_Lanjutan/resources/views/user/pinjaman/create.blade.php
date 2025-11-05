@extends('user.index')

@section('title', 'Ajukan Pinjaman')

@section('content')
    <div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800 text-center">Formulir Pengajuan Pinjaman</h2>

        {{-- Pesan Sukses --}}
        @if(session('success'))
            <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan Error --}}
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Pengajuan --}}
        <form action="{{ route('user.pinjaman.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Nominal Pinjaman</label>
                <input type="number" name="nominal"
                    class="w-full border-gray-300 border rounded-md p-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
                    placeholder="Masukkan jumlah pinjaman (min. 100.000)" required>
            </div>

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 rounded-lg transition duration-200">
                💰 Kirim Pengajuan
            </button>
        </form>
    </div>
@endsection