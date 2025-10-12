@extends('user.index')

@section('title', 'Ajukan Pinjaman')

@section('content')
<div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded-xl shadow">
    <h2 class="text-xl font-semibold mb-4 text-gray-700">Formulir Pengajuan Pinjaman</h2>
    <form action="{{ route('user.pinjaman.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-600">Nominal Pinjaman</label>
            <input type="number" name="nominal" class="w-full border rounded-md p-2" required>
        </div>

        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">Kirim Pengajuan</button>
    </form>
</div>
@endsection

