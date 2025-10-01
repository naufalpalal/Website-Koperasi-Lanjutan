@extends('pengurus.index')

@section('content')
<div class="max-w-lg w-full mx-auto p-6 bg-white rounded-lg shadow-lg mt-8">
    <h3 class="text-2xl font-bold mb-6 text-gray-800 text-center sm:text-left">
        Update Nominal Simpanan Wajib
    </h3>

    {{-- Tombol Kembali --}}
    <div class="mb-4">
      
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
        {{-- Berlaku Mulai --}}
<div>
    <label class="font-medium block mb-2">Berlaku Mulai:</label>
    
    {{-- Opsi: Mulai Hari Ini --}}
    <div class="flex items-center mb-2">
        <input type="radio" id="mulai_hari_ini" name="periode_opsi" value="hari_ini" 
               class="mr-2" checked>
        <label for="mulai_hari_ini">Mulai bulan ini ({{ now()->format(' M Y') }})</label>
    </div>

    {{-- Opsi: Pilih Bulan --}}
    <div class="flex items-center mb-2">
        <input type="radio" id="mulai_bulan" name="periode_opsi" value="custom" class="mr-2">
        <label for="mulai_bulan">Pilih Bulan</label>
    </div>

    {{-- Jika pilih "Pilih Bulan" tampilkan range bulan --}}
    <div id="custom_periode" class="hidden mt-3 space-y-2">
        <div>
            <label for="periode_mulai" class="block text-sm font-medium">Mulai Bulan:</label>
            <input type="month" name="periode_mulai" id="periode_mulai"
                   value="{{ now()->format('Y-m') }}"
                   class="border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
    </div>
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

