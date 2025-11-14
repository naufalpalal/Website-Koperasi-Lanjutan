@extends('Pengurus.index')

@section('content')
<div class="p-6 bg-white rounded shadow">

    <h2 class="text-xl font-bold mb-4">📥 Daftar Pengajuan Angsuran</h2>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    @if ($pengajuan->count())
        <table class="min-w-full text-sm border rounded overflow-hidden">
            <thead class="bg-gray-100 font-semibold text-gray-700">
                <tr>
                    <th class="px-3 py-2 border">#</th>
                    <th class="px-3 py-2 border">Nama</th>
                    <th class="px-3 py-2 border">Pinjaman</th>
                    <th class="px-3 py-2 border">Angsuran</th>
                    <th class="px-3 py-2 border">Bukti Transfer</th>
                    <th class="px-3 py-2 border">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($pengajuan as $i => $p)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-2 text-center">{{ $i + 1 }}</td>

                        <td class="border px-3 py-2">{{ $p->user->nama }}</td>

                        <td class="border px-3 py-2">
                            Rp{{ number_format($p->pinjaman->nominal, 0, ',', '.') }}
                        </td>

                        <td class="border px-3 py-2">
                            @foreach(json_decode($p->angsuran_ids, true) as $ang)
                                <span class="px-2 py-1 bg-gray-200 rounded text-xs">{{ $ang }}</span>
                            @endforeach
                        </td>

                        <td class="border px-3 py-2">
                            <a href="{{ asset('storage/' . $p->bukti_transfer) }}" target="_blank"
                                class="text-blue-600 underline">Lihat</a>
                        </td>

                        <td class="border px-3 py-2 text-center">
                            @if ($p->status === 'pending')
                                <div class="flex gap-2 justify-center">
                                    
                                    {{-- ACC --}}
                                    <form action="{{ route('pengurus.angsuran.acc', $p->id) }}" method="POST">
                                        @csrf
                                        <button
                                            class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                            ✔ ACC
                                        </button>
                                    </form>

                                    {{-- TOLAK --}}
                                    <form action="{{ route('pengurus.angsuran.tolak', $p->id) }}" method="POST">
                                        @csrf
                                        <button
                                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                            ✖ Tolak
                                        </button>
                                    </form>

                                </div>
                            @else
                                <span class="px-3 py-1 rounded text-white
                                    {{ $p->status === 'acc' ? 'bg-green-600' : 'bg-red-600' }}">
                                    {{ strtoupper($p->status) }}
                                </span>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    @else
        <p class="text-gray-600">Tidak ada pengajuan angsuran 🎉</p>
    @endif

</div>
@endsection
