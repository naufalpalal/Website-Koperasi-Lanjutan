@extends('user.index')

@section('title', 'Simpanan Wajib')

@section('content')
    <div class="container mx-auto px-2 sm:px-4 md:px-8 py-4 md:py-6">
        <div class="bg-white shadow-lg rounded-2xl p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-4">Riwayat Simpanan Wajib</h3>
            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div class="mb-4 p-3 rounded-lg border border-green-300 bg-green-50 text-green-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 rounded-lg border border-red-300 bg-red-50 text-red-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif
            <div class="overflow-x-auto">
                <table
                    class="w-full min-w-[600px] text-xs sm:text-sm text-left text-gray-600 border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                        <tr>
                            <th class="px-2 sm:px-4 py-2 sm:py-3">Tahun</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3">Bulan</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3">Nilai</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3">Status</th>
                            <th class="px-2 sm:px-4 py-2 sm:py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody id="anggotaTableBody" class="divide-y divide-gray-200 bg-white">
                        @foreach($simpanan as $row)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-2 sm:px-4 py-2 sm:py-3 font-medium text-gray-700">{{ $row->tahun }}</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3">{{ $row->bulan }}</td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-green-600 font-semibold">Rp
                                    {{ number_format($row->nilai, 0, ',', '.') }}
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3">
                                    @if($row->status == 'Dibayar')
                                        <span
                                            class="px-2 sm:px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Dibayar</span>
                                    @elseif($row->status == 'Gagal')
                                        <span
                                            class="px-2 sm:px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Gagal</span>
                                    @else
                                        <span
                                            class="px-2 sm:px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Diajukan</span>
                                    @endif
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 italic text-gray-500">
                                    @if($row->status == 'Diajukan')
                                        Diajukan: {{ $row->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                                    @elseif($row->status == 'Dibayar')
                                        Dibayar: {{ $row->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                                    @elseif($row->status == 'Gagal')
                                        Gagal: {{ $row->updated_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination controls -->
            <div id="paginationContainer" class="mt-4 flex justify-center"></div>

        </div>
        <!-- Card Kedua: Transfer Manual Simpanan Gagal -->
        <div class="bg-white shadow-sm rounded-lg p-2 sm:p-3 mt-6">
            <div class="flex items-start gap-3 mb-2">
                <div class="w-9 h-9 bg-red-50 rounded-full flex items-center justify-center text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-800">Transfer Manual Simpanan Gagal</h4>
                    <p class="text-xs text-gray-500">Bagi Bapak/Ibu jika ada tagihan simpanan wajib yang gagal, bisa
                        transfer melalui no rekening berikut.</p>
                </div>
            </div>

            <div class="bg-red-50/40 border border-red-100 rounded-md p-2 mb-2">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start">
                    <!-- Nomor Rekening & Salin -->
                    <div class="text-center p-2 bg-white rounded border border-gray-100">
                        <p class="text-xs text-gray-500">Nomor Rekening</p>
                        <p id="noRekening" class="text-lg font-semibold text-gray-800 my-1">7283225229</p>
                    </div>

                    <!-- Bank & Total -->
                    <div class="p-2 bg-white rounded border border-gray-100 text-xs">
                        <p class="text-gray-500 text-xs mb-1">Bank</p>
                        <p class="font-semibold text-gray-800 text-sm">BSI (Bank Syariah Indonesia)</p>
                        @php $totalGagal = $simpanan->where('status', 'Gagal')->sum('nilai'); @endphp
                        <p class="text-gray-500 text-xs mt-2">Total Gagal</p>
                        <p class="text-sm font-bold text-red-600">Rp {{ number_format($totalGagal, 0, ',', '.') }}</p>
                    </div>

                    <!-- Atas Nama -->
                    <div class="p-2 bg-white rounded border border-gray-100 text-xs">
                        <p class="text-gray-500 text-xs mb-1">Atas Nama</p>
                        <p class="font-semibold text-gray-800 text-sm">Koperasi Karyawan Poliwangi</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-md p-2 mb-2 text-xs flex items-start gap-2">
                <svg class="w-4 h-4 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
                </svg>
                <p class="text-xs text-yellow-700 leading-snug">Simpan bukti transfer setelah melakukan pembayaran dan
                    hubungi admin untuk verifikasi.</p>
            </div>

            <form action="{{ route('simpanan-wajib.upload') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col sm:flex-row gap-2 items-start">
                @csrf
                <input type="hidden" name="simpanan_id" value="{{ $row->id ?? '' }}">
                <div class="flex-1">
                    <label class="block text-xs text-gray-700 mb-1">Upload Bukti (JPG/PNG)</label>
                    <input type="file" name="bukti_transfer" accept="image/*" required
                        class="w-full text-xs border border-gray-300 rounded px-2 py-1">
                </div>
                <div class="flex flex-col gap-2 sm:flex-col sm:justify-start">
                    <button type="submit"
                        class="bg-blue-600 text-white px-3 py-1 rounded text-xs font-semibold">Upload</button>
                    @php
                        $bendahara = \App\Models\Pengurus::where('role', 'bendahara')->first();
                        $waNumber = $bendahara ? preg_replace('/[^0-9]/', '', $bendahara->no_telepon) : null;
                    @endphp
                    @if($waNumber)
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank"
                            class="bg-green-600 text-white px-3 py-1 rounded text-xs font-semibold text-center">Hubungi
                            Admin</a>
                    @else
                        <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded text-xs inline-block text-center">Nomor Admin
                            Tidak Tersedia</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection