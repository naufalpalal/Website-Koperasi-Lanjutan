@extends ('pengurus.index')
@extends ('pengurus.layouts.navbar')
@extends ('pengurus.layouts.sidebar')
@section('content')

<div class="container mx-auto px-4 pt-12">
    <div class="mb-8">
        <div class="text-gray-800 font-bold text-xl mb-4">Statistik Koperasi</div>
        <div class="flex flex-wrap gap-6 justify-start">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-lg flex flex-col items-center justify-center aspect-square w-56 hover:shadow-xl transition">
                <span class="bg-blue-500 text-white rounded-full p-3 mt-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M17 20h5v-2a4 4 0 0 0-4-4h-1" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M6 20v-2a4 4 0 0 1 4-4h0a4 4 0 0 1 4 4v2" />
                    </svg>
                </span>
                <div class="mt-6 text-sm text-gray-500">Total Anggota</div>
                <div class="font-bold text-2xl text-gray-700 mb-6">120</div>
            </div>
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl shadow-lg flex flex-col items-center justify-center aspect-square w-56 hover:shadow-xl transition">
                <span class="bg-yellow-500 text-white rounded-full p-3 mt-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="3" y="11" width="18" height="10" rx="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                </span>
                <div class="mt-6 text-sm text-gray-500">Pinjaman Aktif</div>
                <div class="font-bold text-2xl text-gray-700 mb-6">35</div>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl shadow-lg flex flex-col items-center justify-center aspect-square w-56 hover:shadow-xl transition">
                <span class="bg-green-500 text-white rounded-full p-3 mt-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 0V4m0 16v-4" />
                    </svg>
                </span>
                <div class="mt-6 text-sm text-gray-500">Total Simpanan</div>
                <div class="font-bold text-2xl text-gray-700 mb-6">Rp 240.000.000</div>
            </div>
        </div>
    </div>

    <!-- Grafik Perkembangan Koperasi -->
    <div class="mb-8">
        <div class="font-bold text-gray-800 mb-2">Grafik Perkembangan Koperasi</div>
        <div class="bg-white rounded-2xl shadow-lg p-8 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-blue-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path d="M4 20v-6m4 6V10m4 10V4m4 16v-8" />
                <path d="M4 20h16" />
            </svg>
        </div>
    </div>

    <!-- Riwayat Aktivitas Anggota -->
    <div class="mb-8">
        <div class="bg-blue-500 rounded-2xl p-6 text-white shadow-lg">
            <div class="font-bold mb-3">Riwayat Aktivitas Anggota:</div>
            <div class="flex items-center gap-3 mb-2">
                <span class="bg-white bg-opacity-20 rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M16 8l-4.5 4.5L8 10" />
                    </svg>
                </span>
                <span>Andi Mengajukan Pinjaman (10:45)</span>
            </div>
            <!-- Tambahkan aktivitas lain di sini -->
        </div>
    </div>
</div>
@endsection


