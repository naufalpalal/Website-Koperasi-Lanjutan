@extends ('admin.index')

@section('content')
    <div class="container">
        <div class="mb-6">
            <div class="text-gray-700 font-semibold mb-2 ml-10">Statistik Koperasi</div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow p-4 flex items-center gap-3">
                    <span class="text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path d="M17 20h5v-2a4 4 0 0 0-4-4h-1" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M6 20v-2a4 4 0 0 1 4-4h0a4 4 0 0 1 4 4v2" />
                        </svg>
                    </span>
                    <div>
                        <div class="text-xs text-gray-500">Total Anggota</div>
                        <div class="font-bold text-lg">120</div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow p-4 flex items-center gap-3">
                    <span class="text-yellow-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <rect x="3" y="11" width="18" height="10" rx="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </span>
                    <div>
                        <div class="text-xs text-gray-500">Pinjaman Aktif</div>
                        <div class="font-bold text-lg">35</div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow p-4 flex items-center gap-3">
                    <span class="text-green-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 0V4m0 16v-4" />
                        </svg>
                    </span>
                    <div>
                        <div class="text-xs text-gray-500">Total Simpanan</div>
                        <div class="font-bold text-lg">Rp 240.000.000</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Grid -->
        <!-- @include('admin.layouts.menu_grid') -->

        <!-- Grafik Perkembangan Koperasi -->
        <div class="mb-6">
            <div class="font-semibold text-gray-700 mb-2">Grafik Perkembangan Koperasi</div>
            <div class="bg-white rounded-xl shadow p-6 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path d="M4 20v-6m4 6V10m4 10V4m4 16v-8" />
                    <path d="M4 20h16" />
                </svg>
            </div>


        </div>

        <!-- Riwayat Aktivitas Anggota -->
        <div class="mb-6">  
            <div class="bg-blue-400 rounded-xl p-4 text-white">
                <div class="font-semibold mb-2">Riwayat Aktivitas Anggota:</div>
                <div class="flex items-center gap-2 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M16 8l-4.5 4.5L8 10" />
                    </svg>
                    <span>Andi Mengajukan Pinjaman (10:45)</span>
                </div>
                <div class="flex items
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M16 8l-4.5 4.5L8 10" />
                    </svg>
                    <span>Andi Mengajukan Pinjaman (10:45)</span>
                </div>
            </div>
        </div>
    </div>
@endsection


