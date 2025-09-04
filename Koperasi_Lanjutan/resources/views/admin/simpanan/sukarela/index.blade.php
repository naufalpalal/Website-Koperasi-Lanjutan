@extends('admin.index')

@section('content')
<div class="container mx-auto px-6 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-blue-600">Daftar Simpanan Sukarela</h1>
    </div>
    
    <!-- Tabel versi Desktop -->
    <div class="hidden md:block bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">No</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama Anggota</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Jumlah Simpanan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Tanggal</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-blue-50">
                    <td class="px-6 py-4 text-sm">1</td>
                    <td class="px-6 py-4 text-sm">Dimas JP</td>
                    <td class="px-6 py-4 text-sm">Rp 500.000</td>
                    <td class="px-6 py-4 text-sm">05-08-2025</td>
                    <td class="px-6 py-4 text-center">
                        <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded mr-2">Setujui</button>
                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Tolak</button>
                    </td>
                </tr>
                <tr class="hover:bg-blue-50">
                    <td class="px-6 py-4 text-sm">2</td>
                    <td class="px-6 py-4 text-sm">Leo Eka</td>
                    <td class="px-6 py-4 text-sm">Rp 300.000</td>
                    <td class="px-6 py-4 text-sm">04-08-2025</td>
                    <td class="px-6 py-4 text-center">
                        <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded mr-2">Setujui</button>
                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Tolak</button>
                    </td>
                </tr>
                <tr class="hover:bg-blue-50">
                    <td class="px-6 py-4 text-sm">3</td>
                    <td class="px-6 py-4 text-sm">Eric Ardiansyah</td>
                    <td class="px-6 py-4 text-sm">Rp 1.000.000</td>
                    <td class="px-6 py-4 text-sm">10-07-2025</td>
                    <td class="px-6 py-4 text-center">
                        <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded mr-2">Setujui</button>
                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Tolak</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Card versi Mobile -->
    <div class="block md:hidden space-y-4">
        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-sm"><span class="font-semibold text-blue-600">No:</span> 1</p>
            <p class="text-sm"><span class="font-semibold text-blue-600">Nama Anggota:</span> Dimas JP</p>
            <p class="text-sm"><span class="font-semibold text-blue-600">Jumlah Simpanan:</span> Rp 500.000</p>
            <p class="text-sm"><span class="font-semibold text-blue-600">Tanggal:</span> 05-08-2025</p>
            <div class="mt-2 flex space-x-2">
                <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Setujui</button>
                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Tolak</button>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-sm"><span class="font-semibold text-blue-600">No:</span> 2</p>
            <p class="text-sm"><span class="font-semibold text-blue-600">Nama Anggota:</span> Leo Eka</p>
            <p class="text-sm"><span class="font-semibold text-blue-600">Jumlah Simpanan:</span> Rp 300.000</p>
            <p class="text-sm"><span class="font-semibold text-blue-600">Tanggal:</span> 04-08-2025</p>
            <div class="mt-2 flex space-x-2">
                <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Setujui</button>
                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Tolak</button>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <p class="text-sm"><span class="font-semibold text-blue-600">No:</span> 3</p>
            <p class="text-sm"><span class="font-semibold text-blue-600">Nama Anggota:</span> Eric Ardiansyah</p>
            <p class="text-sm"><span class="font-semibold text-blue-600">Jumlah Simpanan:</span> Rp 1.000.000</p>
            <p class="text-sm"><span class="font-semibold text-blue-600">Tanggal:</span> 10-07-2025</p>
            <div class="mt-2 flex space-x-2">
                <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Setujui</button>
                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Tolak</button>
            </div>
        </div>
    </div>
</div>
@endsection