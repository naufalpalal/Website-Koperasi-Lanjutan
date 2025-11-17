@extends('pengurus.index')

@section('content')
    <div class="p-6 bg-white rounded shadow">
        <h2 class="text-xl font-bold mb-4">Persetujuan Simpanan Sukarela</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tombol untuk buka modal --}}
        <div class="mb-4">
            <button command="show-modal" commandfor="dialog"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Pilih Periode
            </button>

            <el-dialog>
                <dialog id="dialog" aria-labelledby="dialog-title"
                    class="fixed inset-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent backdrop:bg-transparent">

                    <el-dialog-backdrop class="fixed inset-0 bg-gray-500/75 transition-opacity data-closed:opacity-0 
                               data-enter:duration-300 data-enter:ease-out data-leave:duration-200 
                               data-leave:ease-in dark:bg-gray-900/50">
                    </el-dialog-backdrop>

                    <div tabindex="0" class="flex min-h-full items-end justify-center p-4 text-center focus:outline-none 
                               sm:items-center sm:p-0">

                        <el-dialog-panel
                            class="bg-white rounded-lg shadow-lg p-6 w-96 text-center transform transition-all">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                                Pilih Periode Generate
                            </h2>

                            <form id="periodeForm" action="{{ route('pengurus.simpanan.sukarela.generate') }}"
                                method="POST">
                                @csrf

                                <!-- Pilih Bulan -->
                                <div class="mb-4">
                                    <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">
                                        Bulan
                                    </label>

                                    <select name="bulan" id="bulan" class="w-full border rounded px-3 py-2">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">
                                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <!-- Pilih Tahun -->
                                <div class="mb-4">
                                    <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tahun
                                    </label>

                                    <input type="number" name="tahun" id="tahun" value="{{ now()->year }}"
                                        class="w-full border rounded px-3 py-2">
                                </div>

                                <!-- Tombol Aksi -->
                                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 dark:bg-gray-700/25">

                                    <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 
                text-sm font-semibold text-white shadow-xs hover:bg-red-500 sm:ml-3 sm:w-auto 
                dark:bg-red-500 dark:shadow-none dark:hover:bg-red-400">
                                        Generate
                                    </button>


                                    <button type="button" command="close" commandfor="dialog" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 
                                           text-sm font-semibold text-gray-900 shadow-xs inset-ring inset-ring-gray-300 
                                           hover:bg-gray-50 sm:mt-0 sm:w-auto dark:bg-white/10 dark:text-white 
                                           dark:shadow-none dark:inset-ring-white/5 dark:hover:bg-white/20">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </el-dialog-panel>
                    </div>
                </dialog>
            </el-dialog>
        </div>

        <form action="{{ route('pengurus.simpanan.sukarela.update') }}" method="POST">
            @csrf
            <table class="min-w-full table-fixed border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="w-12 px-4 py-2 border text-center"> <!-- checkbox center -->
                            <input type="checkbox" id="checkAll">
                        </th>
                        <th class="w-40 px-4 py-2 border text-left">Nama Anggota</th>
                        <th class="w-24 px-4 py-2 border text-left">Bulan</th>
                        <th class="w-20 px-4 py-2 border text-left">Tahun</th>
                        <th class="w-32 px-4 py-2 border text-left">Nominal</th>
                        <th class="w-28 px-4 py-2 border text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($simpanan as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border text-center">
                                <input type="checkbox" name="anggota[]" value="{{ $item->id }}">
                            </td>
                            <td class="px-4 py-2 border">{{ $item->user->nama ?? '-' }}</td>
                            <td class="px-4 py-2 border">{{ $item->bulan }}</td>
                            <td class="px-4 py-2 border">{{ $item->tahun }}</td>
                            <td class="px-4 py-2 border">Rp {{ number_format($item->nilai, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border">{{ $item->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Simpan Persetujuan
                </button>
            </div>
        </form>



        <form action="{{ route('pengurus.simpanan.sukarela.pengajuan') }}" method="GET" class="mt-4">
            @csrf
            <div>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    Lihat Pengajuan Baru
                </button>
            </div>
        </form>

        <div class="mt-4">
            <a href="{{ route('pengurus.simpanan.sukarela.riwayat') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                Lihat Riwayat Simpanan Sukarela
            </a>
        </div>
    </div>
@endsection