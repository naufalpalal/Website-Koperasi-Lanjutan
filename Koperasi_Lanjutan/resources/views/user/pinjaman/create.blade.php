@extends('user.index')

@section('title', 'Ajukan Pinjaman')

@section('content')
    <div class="p-6 bg-white rounded shadow">

        <h3 class="text-xl font-semibold mb-4">Pilih Paket Pinjaman</h3>

        <div class="grid md:grid-cols-3 gap-4">
            @foreach ($paketPinjaman as $paket)
                <div class="border rounded p-4 bg-gray-50 shadow-sm">
                    <h4 class="font-bold text-lg mb-2">{{ $paket->nama_paket }}</h4>

                    <p><strong>Nominal:</strong> Rp {{ number_format($paket->nominal, 0, ',', '.') }}</p>
                    <p><strong>Tenor:</strong> {{ $paket->tenor }} bulan</p>
                    <p><strong>Bunga:</strong> {{ $paket->bunga }}%</p>

                    <form action="{{ route('user.pinjaman.store', $paket->id) }}" method="POST" class="mt-4">
                        @csrf
                        <button class="bg-green-600 text-white px-4 py-2 rounded w-full hover:bg-green-700">
                            Ajukan Paket Ini
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    {{-- MODAL --}}
    @if (session('modal'))
        <x-alert-modal title="{{ session('modal.title') }}" type="{{ session('modal.type') }}">
            {{ session('modal.message') }}
        </x-alert-modal>

    @endif
@endsection