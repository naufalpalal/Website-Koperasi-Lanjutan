@extends('user.index')

@section('title', 'Simpanan Wajib')

@section('content')
    <div class="container mx-auto px-2 sm:px-4 md:px-8 py-4 md:py-6">
        <div class="bg-white shadow-lg rounded-2xl p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-4">Riwayat Simpanan Wajib</h3>

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
        <div class="bg-white shadow-lg rounded-2xl p-4 sm:p-6 mt-6">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-700">Transfer Manual Simpanan Gagal</h3>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-orange-50 border border-red-200 rounded-xl p-4 sm:p-6">
                <p class="text-sm text-gray-600 mb-4">
                    Jika terdapat simpanan wajib yang gagal terpotong secara otomatis, silakan lakukan transfer manual ke
                    rekening koperasi berikut:
                </p>

                <div class="bg-white rounded-lg p-4 mb-4 border border-gray-200 flex flex-col items-center text-center">
                    <h3 class="text-base font-semibold text-gray-800 mb-2">Scan QR untuk Melakukan Pembayaran</h3>

                    <!-- Gambar QR -->
                    <div class="w-48 h-48 mb-4">
                        <img src="{{ asset('assets/moh.naufal16_qr.png') }}" alt="QR Code Pembayaran"
                            class="w-full h-full object-contain">
                    </div>

                    <!-- Keterangan Tambahan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-left w-full max-w-md">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Bank</p>
                            <p class="text-sm font-semibold text-gray-800">Bank nya Mas Naufal</p>
                        </div>
                        <!-- <div>
                            <p class="text-xs text-gray-500 mb-1">Nomor Rekening</p>
                            <p class="text-sm font-semibold text-gray-800">1234-5678-9012-3456</p>
                        </div> -->
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Atas Nama</p>
                            <p class="text-sm font-semibold text-gray-800">Koperasi Politeknik Negeri Banyuwangi</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Total Simpanan Gagal</p>
                            @php
                                $totalGagal = $simpanan->where('status', 'Gagal')->sum('nilai');
                            @endphp
                            <p class="text-sm font-bold text-red-600">Rp {{ number_format($totalGagal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>


                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <p class="text-xs text-gray-700">
                            Setelah melakukan transfer, harap simpan bukti transfer dan hubungi admin untuk konfirmasi
                            pembayaran.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-2">
                    <button
                        class="flex-1 bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:from-blue-600 hover:to-indigo-700 transition duration-200 shadow-md">
                        Upload Bukti Transfer
                    </button>
                    <button
                        class="flex-1 bg-white text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 hover:bg-gray-50 transition duration-200">
                        Hubungi Admin
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection