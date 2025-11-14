@extends('Pengurus.index')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="p-6 bg-white rounded shadow">
        <h2 class="text-xl font-bold mb-4">
            📅 Daftar Potongan Pinjaman Bulan
            {{ $selectedPeriod instanceof \Carbon\Carbon ? $selectedPeriod->locale('id')->translatedFormat('F Y') : 'Tidak Diketahui' }}
        </h2>

        {{-- Filter periode + tombol di kanan --}}
        <div class="mb-4 flex items-center justify-between">
            <form method="GET" action="{{ route('pengurus.pinjaman.pemotongan') }}" class="flex items-center gap-2">
                @csrf

                <label for="periode" class="text-sm">Periode</label>
                <input id="periode" name="periode" type="month" value="{{ request('periode', now()->format('Y-m')) }}"
                    class="border rounded px-2 py-1">
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Filter</button>
                <a href="{{ route('pengurus.pinjaman.pemotongan') }}" class="text-sm text-gray-600 ml-4">Reset</a>
            </form>

            <a href="{{ route('pengurus.pinjaman.pengajuan') }}"
                class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded transition duration-150 ease-in-out">
                Daftar Pengajuan
            </a>

             <a href="{{ route('pengurus.settings.index') }}"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                ⚙️ Setting Tenor & Bunga    
            </a>
        </div>

        @if ($angsuran->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 text-sm">
                    <thead class="bg-gray-100">
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
                            <tr id="row-{{ $a->id }}">
                                <td class="px-3 py-2 border text-center">{{ $idx + 1 }}</td>
                                <td class="px-3 py-2 border">{{ $a->pinjaman->user->nama ?? '-' }}</td>
                                <td class="px-3 py-2 border text-right">
                                    Rp{{ number_format($a->pinjaman->nominal, 0, ',', '.') }}</td>
                                <td class="px-3 py-2 border text-center">{{ $a->bulan_ke }}</td>
                                <td class="px-3 py-2 border text-right">
                                    Rp{{ number_format($a->jumlah_bayar, 0, ',', '.') }}</td>
                                <td class="px-3 py-2 border text-center">
                                    {{ $a->tanggal_bayar ? \Carbon\Carbon::parse($a->tanggal_bayar)->translatedFormat('d F Y') : '-' }}
                                </td>

                                <td class="px-3 py-2 border text-center">
                                    <input type="checkbox" class="mark-paid" data-id="{{ $a->id }}"
                                        {{ $a->status === 'lunas' ? 'checked' : '' }}>
                                </td>
                                <td class="px-3 py-2 border text-center" id="status-{{ $a->id }}">
                                    <span
                                        class="px-2 py-1 rounded text-white {{ $a->status === 'lunas' ? 'bg-green-600' : 'bg-red-500' }}">
                                        {{ ucfirst($a->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600">Tidak ada potongan pinjaman pada periode ini 🎉</p>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            document.querySelectorAll('.mark-paid').forEach(cb => {
                cb.addEventListener('change', async function() {
                    const id = this.dataset.id;
                    const newStatus = this.checked ? 'lunas' : 'belum lunas';
                    const confirmText = newStatus === 'lunas' ?
                        'Tandai angsuran ini sebagai Lunas?' :
                        'Kembalikan status ke Belum Lunas?';

                    if (!confirm(confirmText)) {
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
                            const statusEl = document.getElementById('status-' + id);
                            statusEl.innerHTML = `
                        <span class="px-2 py-1 rounded text-white ${
                            data.status === 'lunas' ? 'bg-green-600' : 'bg-red-500'
                        }">${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</span>
                    `;
                        } else {
                            alert(data.message || 'Gagal memperbarui status.');
                            this.checked = !this.checked;
                        }
                    } catch (err) {
                        console.error(err);
                        alert('Kesalahan jaringan. Silakan coba lagi.');
                        this.checked = !this.checked;
                    }
                });
            });
        });
    </script>
@endsection
