@extends('user.index')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-8">
    <h2 class="text-2xl sm:text-3xl font-semibold mb-6 text-center sm:text-left">Simpanan Sukarela</h2>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form pengajuan full width --}}
    <div class="bg-white p-6 sm:p-8 rounded-lg shadow-md mt-6 w-full">
        <form action="{{ route('user.simpanan.sukarela.store') }}" method="POST" class="space-y-4 w-full">
            @csrf
            <div>
                <label for="amount" class="block font-medium mb-1">Nominal Pengajuan</label>
                <input type="number" id="amount" name="amount" min="1000" required 
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       placeholder="Masukkan nominal pengajuan">
            </div>

            <div>
                <label for="month" class="block font-medium mb-1">Bulan Simpanan</label>
                <input type="month" id="month" name="month" required
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="note" class="block font-medium mb-1">Catatan (opsional)</label>
                <textarea id="note" name="note" placeholder="Alasan pengajuan"
                          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="text-center sm:text-left">
                <button type="submit" 
                        class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 transition-colors w-full sm:w-auto">
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
