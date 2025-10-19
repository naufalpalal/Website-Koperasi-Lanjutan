@extends('user.index')

@section('title', 'Simpanan Wajib')

@section('content')
<div class="container mx-auto px-2 sm:px-4 md:px-8 py-4 md:py-6">
    <div class="bg-white shadow-lg rounded-2xl p-4 sm:p-6">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-4">Riwayat Simpanan Wajib</h3>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] text-xs sm:text-sm text-left text-gray-600 border border-gray-200 rounded-lg overflow-hidden">
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
                            <td class="px-2 sm:px-4 py-2 sm:py-3 text-green-600 font-semibold">Rp {{ number_format($row->nilai, 0, ',', '.') }}</td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3">
                                @if($row->status == 'Dibayar')
                                    <span class="px-2 sm:px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Dibayar</span>
                                @elseif($row->status == 'Gagal')
                                    <span class="px-2 sm:px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Gagal</span>
                                @else
                                    <span class="px-2 sm:px-3 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Diajukan</span>
                                @endif
                            </td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3 italic text-gray-500">{{ $row->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination controls -->
        <div id="paginationContainer" class="mt-4 flex justify-center"></div>

    </div>
</div>
@endsection
