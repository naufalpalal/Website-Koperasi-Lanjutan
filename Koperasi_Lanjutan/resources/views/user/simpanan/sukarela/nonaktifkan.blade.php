@extends('user.index')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Nonaktifkan Simpanan Sukarela</h2>

    <form action="{{ route('simpanan.sukarela.nonaktifkan.submit') }}" method="POST">
        @csrf

        <label for="nonaktif_hingga" class="block mb-2 font-semibold">Nonaktifkan hingga tanggal:</label>
        <input type="date" name="nonaktif_hingga" id="nonaktif_hingga"
            class="border rounded p-2 mb-4 w-full" required>

        <button type="submit"
            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition duration-200">
            Konfirmasi Nonaktifkan
        </button>

        <a href="{{ route('user.simpanan.sukarela.index') }}"
            class="ml-3 px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400 transition duration-200">
            Batal
        </a>

    </form>
</div>
@endsection
