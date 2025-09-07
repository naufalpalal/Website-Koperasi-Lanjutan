@extends('user.index')

@section('title', 'Simpanan Sukarela')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-xl shadow-md">
        <h2 class="text-xl font-semibold mb-4">Ajukan Simpanan Sukarela</h2>

        @if (session('success'))
            <div class="p-3 mb-4 text-green-700 bg-green-100 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('simpanan.sukarela.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="amount" class="block text-sm font-medium">Jumlah Simpanan</label>
                <input type="number" name="amount" id="amount" class="w-full mt-1 p-2 border rounded-lg"
                    placeholder="Minimal 1000" required>
            </div>

            <div>
                <label for="month" class="block text-sm font-medium">Bulan</label>
                <input type="month" name="month" id="month" class="w-full mt-1 p-2 border rounded-lg" required>
            </div>

            <div>
                <label for="note" class="block text-sm font-medium">Catatan (opsional)</label>
                <textarea name="note" id="note" rows="2" class="w-full mt-1 p-2 border rounded-lg"></textarea>
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Ajukan Simpanan
            </button>
        </form>
    </div>
@endsection
