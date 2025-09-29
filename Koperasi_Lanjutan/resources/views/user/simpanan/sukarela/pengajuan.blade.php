@extends('user.index')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-700">Ajukan Perubahan Simpanan Sukarela</h2>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300">
                {{ session('success') }}
            </div>
        @endif (session('error'))
            <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-300">
                {{ session('error') }}
            </div>

        {{-- Form --}}
        <form action="{{ route('user.simpanan.sukarela.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="nilai" class="block mb-1 text-sm font-semibold text-gray-700">Nominal Baru</label>
                <input type="number" name="nilai" id="nilai" min="1000"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500"
                    placeholder="Masukkan nominal baru" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="bulan" class="block mb-1 text-sm font-semibold text-gray-700">Bulan Berlaku</label>
                    <select name="bulan" id="bulan"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500"
                        required>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="tahun" class="block mb-1 text-sm font-semibold text-gray-700">Tahun Berlaku</label>
                    <select name="tahun" id="tahun"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-blue-200 focus:border-blue-500"
                        required>
                        @for ($i = now()->year; $i <= now()->year + 2; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <button type="submit"
                class="px-6 py-2 rounded-lg bg-blue-600 text-white font-semibold shadow hover:bg-blue-700 transition">
                Ajukan Perubahan
            </button>
        </form>
    </div>
@endsection
