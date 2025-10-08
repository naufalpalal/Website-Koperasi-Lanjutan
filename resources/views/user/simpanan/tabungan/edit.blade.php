@extends('user.index')

@section('title', 'Edit Tabungan')

@section('content')
<div class="container mx-auto px-6 py-10">
    <div class="bg-white shadow-lg rounded-xl p-6">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Edit Tabungan</h2>

        {{-- Error --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.simpanan.tabungan.update', $tabungan->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nilai" class="block text-gray-700 font-medium">Jumlah Tabungan</label>
                <input type="number" name="nilai" id="nilai" value="{{ old('nilai', $tabungan->nilai) }}"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300" required>
            </div>

            <div>
                <label for="tanggal" class="block text-gray-700 font-medium">Tanggal Menabung</label>
                <input type="date" name="tanggal" id="tanggal" 
                    value="{{ old('tanggal', \Carbon\Carbon::parse($tabungan->tanggal)->format('Y-m-d')) }}"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300" required>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection