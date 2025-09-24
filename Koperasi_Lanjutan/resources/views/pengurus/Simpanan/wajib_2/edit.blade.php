@extends('pengurus.index')

@section('content')
<div class="max-w-lg w-full mx-auto p-6 bg-white rounded-lg shadow-lg mt-8">
    <h3 class="text-2xl font-bold mb-6 text-gray-800 text-center sm:text-left">
        Update Nominal Simpanan Wajib
    </h3>

    {{-- Tombol Kembali --}}
    <div class="mb-4">
        <a href="{{ url()->previous() }}"
           class="inline-block bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition text-sm sm:text-base">
           &larr; Kembali
        </a>
    </div>

    {{-- Form update nominal --}}
    <form action="{{ route('pengurus.simpanan.wajib_2.updateNominal') }}" method="POST" class="space-y-4">
        @csrf

        {{-- Nominal --}}
       <div>
    <label for="nilai" class="font-medium block mb-1">Nominal Simpanan Wajib:</label>
    <div class="flex">
        <span class="inline-flex items-center px-3 rounded-l border border-r-0 bg-gray-100 text-gray-700">
            Rp
        </span>
        <input type="number" name="nilai" id="nilai" min="0" required
               class="border rounded-r px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-400 appearance-none"
               placeholder="Masukkan nominal"
               onkeydown="return event.keyCode !== 38 && event.keyCode !== 40"
               onwheel="this.blur()"
               style="appearance: textfield; -moz-appearance: textfield;">
    </div>
</div>

        {{-- Berlaku Sampai Bulan --}}
        <div>
            <label for="periode_selesai" class="font-medium block mb-1">Berlaku Sampai Bulan:</label>
            <input type="month" name="periode_selesai" id="periode_selesai"
            value="{{ isset($master->periode_selesai) ? $master->periode_selesai->format('Y-m') : now()->addMonth()->format('Y-m') }}"
            class="border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4">
            <a href="{{ route('pengurus.simpanan.wajib_2.index') }}"
               class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition text-center">
               Batal
            </a>
            <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Update
            </button>
        </div>
    </form>
</div>
@endsection

