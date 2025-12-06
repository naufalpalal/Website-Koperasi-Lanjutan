{{-- Modal --}}
<input type="checkbox" id="modal-periode" class="peer hidden">

<div class="fixed inset-0 bg-black/40 hidden peer-checked:flex items-center justify-center p-4">

    <label for="modal-periode" class="absolute inset-0"></label>

    <div class="bg-white rounded-lg shadow-xl p-6 w-96 relative z-10">

        <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Pilih Periode Generate
        </h2>

        <form action="{{ route('pengurus.simpanan.sukarela.generate') }}" method="POST">
            @csrf

            {{-- Pilihan Bulan --}}
            <div class="mb-4 text-left">
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>

                @php
                    $tahunDipilih = old('tahun', now()->year);
                    $tahunSekarang = now()->year;
                    $bulanSekarang = now()->month;

                    // Jika tahun yang dipilih adalah tahun sekarang → hanya bulan mulai bulan ini sampai bulan 12
                    // Jika tahun depan → tampilkan semua bulan
                    $startBulan = $tahunDipilih == $tahunSekarang ? $bulanSekarang : 1;
                    $endBulan = 12;
                @endphp

                <select name="bulan" class="w-full border rounded px-3 py-2 focus:ring-blue-500">
                    @for ($i = $startBulan; $i <= $endBulan; $i++)
                        <option value="{{ $i }}">
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="mb-4 text-left">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <input type="number" name="tahun" value="{{ old('tahun', now()->year) }}"
                    class="w-full border rounded px-3 py-2 focus:ring-blue-500">
            </div>


            <div class="flex justify-end gap-3 pt-3 border-t">
                <button type="submit"
                    class="btn btn-primary">
                    Generate
                </button>


                <label for="modal-periode"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 cursor-pointer">
                    Batal
                </label>
            </div>
        </form>

    </div>
</div>