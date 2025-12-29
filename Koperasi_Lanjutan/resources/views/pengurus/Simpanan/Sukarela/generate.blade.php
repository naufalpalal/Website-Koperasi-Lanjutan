@extends('pengurus.index')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow-sm border">

        {{-- ================= HEADER ================= --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-700">
                Generate Simpanan Sukarela
            </h2>

            <a href="{{ route('pengurus.simpanan.sukarela.index') }}"
                class="text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded">
                ← Kembali
            </a>
        </div>

        {{-- ================= ALERT ================= --}}
        @if (session('success'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- ================= MODAL TRIGGER ================= --}}
        <div class="flex flex-wrap items-center gap-3 mb-5">
            <label for="modal-generate"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm cursor-pointer">
                + Generate Periode
            </label>

            <a href="{{ route('pengurus.simpanan.sukarela.download') }}"
                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                Export Excel
            </a>
        </div>
        <input type="checkbox" id="modal-generate" class="peer hidden">

        {{-- ================= MODAL ================= --}}
        <div class="fixed inset-0 bg-black/40 hidden peer-checked:flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">

                {{-- Modal Header --}}
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">
                        Generate Simpanan Sukarela
                    </h3>

                    <label for="modal-generate" class="cursor-pointer text-gray-400 hover:text-gray-600">
                        ✕
                    </label>
                </div>

                {{-- Modal Form --}}
                <form action="{{ route('pengurus.simpanan.sukarela.generate') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-2 gap-4 mb-4">

                        {{-- Bulan --}}
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Bulan</label>
                            <select name="bulan" id="bulanSelect" class="w-full border rounded px-3 py-2 text-sm">
                            </select>
                        </div>



                        {{-- Tahun --}}
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tahun</label>
                            <select name="tahun" id="tahunSelect" class="w-full border rounded px-3 py-2 text-sm">
                                @for ($t = now()->year; $t <= now()->year + 1; $t++)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endfor
                            </select>
                        </div>


                    </div>

                    {{-- Modal Action --}}
                    <div class="flex justify-end gap-2">
                        <label for="modal-generate"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded text-sm cursor-pointer">
                            Batal
                        </label>

                        <button type="submit" onclick="return confirm('Generate simpanan sukarela untuk periode ini?')"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
                            Generate
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- ================= FORM UPDATE ================= --}}
        <form action="{{ route('pengurus.simpanan.sukarela.update') }}" method="POST" class="mt-6">
            @csrf

            {{-- Check All --}}
            <div class="mb-3 flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" id="checkAll"
                    onclick="document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked)"
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded">

                <label for="checkAll" class="cursor-pointer select-none">
                    Pilih semua simpanan
                </label>
            </div>

            {{-- Table --}}
            <div class="overflow-auto rounded-lg border">
                <table class="w-full text-sm border-collapse">

                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-4 py-2 border text-left font-normal">Nama</th>
                            <th class="px-4 py-2 border text-left font-normal">Bulan</th>
                            <th class="px-4 py-2 border text-left font-normal">Tahun</th>
                            <th class="px-4 py-2 border text-left font-normal">Nominal</th>
                            <th class="px-4 py-2 border text-left font-normal">Status</th>
                            <th class="px-4 py-2 border text-center font-normal w-12">pilih</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($simpanan as $item)
                            <tr class="hover:bg-gray-50 text-gray-700">
                                <td class="px-4 py-2 border">
                                    {{ optional($item->user)->nama ?? '-' }}
                                </td>

                                <td class="px-4 py-2 border">{{ $item->bulan }}</td>
                                <td class="px-4 py-2 border">{{ $item->tahun }}</td>

                                <td class="px-4 py-2 border">
                                    Rp {{ number_format($item->nilai, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-2 border">
                                    <span
                                        class="px-2 py-1 rounded text-xs
                                                                                                                            @if ($item->status === 'Diajukan') bg-yellow-100 text-yellow-700
                                                                                                                            @elseif ($item->status === 'Disetujui') bg-green-100 text-green-700
                                                                                                                            @else bg-gray-100 text-gray-600
                                                                                                                            @endif">
                                        {{ $item->status }}
                                    </span>
                                </td>

                                <td class="px-4 py-2 border text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}"
                                        class="row-check h-4 w-4 text-blue-600 border-gray-300 rounded">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-gray-500 text-sm">
                                    Tidak ada data untuk diproses
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Action --}}
            <div class="mt-4 flex justify-end">
                <button type="submit" onclick="return confirm('Setujui simpanan yang dipilih?')"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const tahunSekarang = {{ now()->year }};
        const bulanSekarang = {{ now()->month }};

        const tahunSelect = document.getElementById('tahunSelect');
        const bulanSelect = document.getElementById('bulanSelect');

        const bulanNama = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        function renderBulan() {
            const tahunDipilih = parseInt(tahunSelect.value);
            bulanSelect.innerHTML = '';

            let startBulan = 1;

            // Kalau tahun sekarang → mulai dari bulan sekarang
            if (tahunDipilih === tahunSekarang) {
                startBulan = bulanSekarang;
            }

            for (let b = startBulan; b <= 12; b++) {
                const opt = document.createElement('option');
                opt.value = b;
                opt.textContent = bulanNama[b];
                bulanSelect.appendChild(opt);
            }

            // auto pilih bulan pertama biar gak nyangkut
            if (bulanSelect.options.length > 0) {
                bulanSelect.selectedIndex = 0;
            }
        }

        tahunSelect.addEventListener('change', renderBulan);

        renderBulan(); // initial load
    });
</script>