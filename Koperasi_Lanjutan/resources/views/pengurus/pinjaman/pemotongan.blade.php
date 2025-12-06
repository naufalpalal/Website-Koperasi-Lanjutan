@extends('Pengurus.index')

@section('content')
    <div class="p-6 bg-white rounded-xl shadow-lg">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold flex items-center gap-2">
                📅 Potongan Pinjaman —
                <span class="text-blue-600">
                    {{ $selectedPeriod instanceof \Carbon\Carbon ? $selectedPeriod->locale('id')->translatedFormat('F Y') : 'Tidak diketahui' }}
                </span>
            </h2>
        </div>

        {{-- Filter Periode + Tombol Aksi --}}
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">

            {{-- FILTER --}}
            <form method="GET" action="{{ route('pengurus.pinjaman.pemotongan') }}"
                class="flex items-center gap-3 bg-gray-50 p-3 rounded-lg shadow-sm">

                @csrf
                <div>
                    <label for="periode" class="text-sm font-medium">Periode:</label>
                    <input id="periode" name="periode" type="month"
                        value="{{ request('periode', now()->format('Y-m')) }}"
                        class="border rounded px-3 py-1 focus:ring-blue-400">
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition">
                    Filter
                </button>

                <a href="{{ route('pengurus.pinjaman.pemotongan') }}" class="text-sm text-gray-600 hover:underline">
                    Reset
                </a>
            </form>

            {{-- ACTION BUTTONS --}}
            <div class="flex items-center gap-2">

                <a href="{{ route('pengurus.pinjaman.pengajuan') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white py-1.5 px-3 rounded-md text-sm shadow transition">
                    📄 Daftar Pengajuan
                </a>

                <a href="{{ route('pengurus.settings.index') }}"
                    class="bg-green-600 hover:bg-green-700 text-white py-1.5 px-3 rounded-md text-sm shadow transition">
                    ⚙️ Setting Tenor & Bunga
                </a>

                <a href="{{ route('pengurus.angsuran.pengajuan') }}"
                    class="bg-purple-600 hover:bg-purple-700 text-white py-1.5 px-3 rounded-md text-sm shadow transition">
                    Pengajuan Angsuran
                </a>

                {{-- TOMBOL TAMBAHAN --}}
                <a href="{{ route('pengurus.pinjaman.download') }}"
                    class="bg-purple-600 hover:bg-purple-700 text-white py-1.5 px-3 rounded-md text-sm shadow transition">
                    📥 Export Excel
                </a>

            </div>

        </div>

        {{-- TABEL PEMOTONGAN --}}
        @if ($angsuran->count() > 0)
            <div class="overflow-x-auto rounded-lg border shadow-sm">
                <table class="min-w-full text-sm border-collapse">
                    <thead class="bg-gray-100 font-semibold text-gray-700">
                        <tr>
                            <th class="px-3 py-2 border">#</th>
                            <th class="px-3 py-2 border">Nama Anggota</th>
                            <th class="px-3 py-2 border">Nominal Pinjaman</th>
                            <th class="px-3 py-2 border">Angsuran Ke</th>
                            <th class="px-3 py-2 border">Jumlah Bayar</th>
                            <th class="px-3 py-2 border">Jatuh Tempo</th>
                            <th class="px-3 py-2 border">Catat Lunas</th>
                            <th class="px-3 py-2 border">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($angsuran as $idx => $a)
                            <tr id="row-{{ $a->id }}" class="hover:bg-gray-50 transition">
                                <td class="px-3 py-2 border text-center">{{ $idx + 1 }}</td>

                                <td class="px-3 py-2 border">
                                    {{ $a->pinjaman->user->nama ?? '-' }}
                                </td>

                                <td class="px-3 py-2 border text-right">
                                    Rp{{ number_format($a->pinjaman->nominal, 0, ',', '.') }}
                                </td>

                                <td class="px-3 py-2 border text-center">{{ $a->bulan_ke }}</td>

                                <td class="px-3 py-2 border text-right">
                                    Rp{{ number_format($a->jumlah_bayar, 0, ',', '.') }}
                                </td>

                                <td class="px-3 py-2 border text-center">
                                    {{ $a->tanggal_bayar ? \Carbon\Carbon::parse($a->tanggal_bayar)->translatedFormat('d F Y') : '-' }}
                                </td>

                                <td class="px-3 py-2 border text-center">

                                    <form action="{{ route('pengurus.pinjaman.updateStatus', $a->id) }}" method="POST"
                                        class="flex items-center justify-center gap-2">
                                        @csrf
                                        <input type="hidden" name="status"
                                            value="{{ $a->status === 'lunas' ? 'belum lunas' : 'lunas' }}">

                                        @if ($a->status !== 'lunas')
                                            <input type="number" name="diskon" placeholder="Diskon (Rp)"
                                                class="w-24 text-xs border rounded px-2 py-1 focus:ring-blue-400 focus:border-blue-400"
                                                min="0">
                                        @else
                                            <div class="text-xs text-gray-600 mr-2">
                                                @if ($a->diskon > 0)
                                                    <span class="text-green-600 font-semibold">Diskon:
                                                        Rp{{ number_format($a->diskon, 0, ',', '.') }}</span>
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        @endif

                                        <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center rounded border 
                                            text-white text-sm font-bold leading-none shadow-sm
                                            {{ $a->status === 'lunas' ? 'bg-green-600 border-green-700 hover:bg-green-700' : 'bg-red-600 border-red-700 hover:bg-red-700' }}
                                            transition"
                                            title="{{ $a->status === 'lunas' ? 'Batalkan Pelunasan' : 'Tandai Lunas' }}">

                                            @if ($a->status === 'lunas')
                                                ✔
                                            @else
                                                ✖
                                            @endif

                                        </button>

                                    </form>

                                </td>


                                <td class="px-3 py-2 border text-center" id="status-{{ $a->id }}">
                                    <span
                                        class="px-2 py-1 rounded text-white 
                                    {{ $a->status === 'lunas' ? 'bg-green-600' : 'bg-red-500' }}">
                                        {{ ucfirst($a->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        @else
            <p class="text-gray-600 mt-4 text-center">Tidak ada potongan pinjaman pada periode ini 🎉</p>
        @endif
    </div>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            document.querySelectorAll('.mark-paid').forEach(cb => {
                cb.addEventListener('change', async function() {
                    const id = this.dataset.id;
                    const newStatus = this.checked ? 'lunas' : 'belum lunas';

                    if (!confirm("Ubah status angsuran menjadi: " + newStatus + "?")) {
                        this.checked = !this.checked;
                        return;
                    }

                    try {
                        const res = await fetch(
                        `{{ url('/pengurus/pinjaman') }}/${id}/status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: newStatus
                            })
                        });

                        const data = await res.json();

                        if (data.success) {
                            const el = document.getElementById('status-' + id);
                            el.innerHTML = `
                        <span class="px-2 py-1 rounded text-white ${data.status === 'lunas' ? 'bg-green-600' : 'bg-red-500'}">
                            ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}
                        </span>`;
                        } else {
                            alert(data.message || 'Gagal memperbarui status.');
                            this.checked = !this.checked;
                        }

                    } catch (err) {
                        alert('Terjadi kesalahan jaringan.');
                        this.checked = !this.checked;
                    }
                });
            });
        });
    </script> --}}

@endsection
