@extends('pengurus.index')
@extends('pengurus.layouts.sidebar')

@section('content')
    @php
        $pengurus = Auth::guard('pengurus')->user();
        $roles = [
            'superadmin' => 'Super Admin',
            'ketua' => 'Ketua',
            'bendahara' => 'Bendahara',
            'sekretaris' => 'Sekretaris',
        ];
        $roleLabel = $pengurus ? ($roles[$pengurus->role] ?? $pengurus->role) : '';
    @endphp

    <div class="px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard {{ $roleLabel }}</h1>
            <p class="text-gray-600">Ringkasan operasional dan statistik koperasi</p>
        </div>

        <!-- Stats Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-12 gap-4 mb-6">
            <!-- Total Anggota -->
            <div class="xl:col-span-3">
                <div
                    class="bg-white rounded-lg shadow border-l-4 border-blue-500 p-5 hover:-translate-y-1 transition-transform">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-semibold text-blue-600 uppercase mb-1">Total Anggota</div>
                            <div class="text-2xl font-bold text-gray-800">{{ $totalAnggota }}</div>
                        </div>
                        <div class="text-gray-300">
                            <i class="fas fa-users text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pinjaman -->
            <div class="xl:col-span-3">
                <div
                    class="bg-white rounded-lg shadow border-l-4 border-green-500 p-5 hover:-translate-y-1 transition-transform">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="text-xs font-semibold text-green-600 uppercase mb-1">Total Pinjaman Masuk</div>
                            <div class="text-2xl font-bold text-gray-800">Rp
                                {{ number_format($totalPinjaman, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-500 mt-1">Dibayar: Rp
                                {{ number_format($totalPinjamanDibayar, 0, ',', '.') }}</div>
                        </div>
                        <div class="text-gray-300">
                            <i class="fas fa-hand-holding-usd text-3xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Simpanan -->
            <div class="xl:col-span-6">
                <div
                    class="bg-white rounded-lg shadow border-l-4 border-cyan-500 p-5 hover:-translate-y-1 transition-transform h-full">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <div class="text-xs font-semibold text-cyan-600 uppercase mb-1">Total Simpanan</div>
                            <div class="text-2xl font-bold text-gray-800">Rp
                                {{ number_format($totalSimpanan, 0, ',', '.') }}</div>
                        </div>
                        <div class="text-gray-300">
                            <i class="fas fa-piggy-bank text-3xl"></i>
                        </div>
                    </div>

                    <!-- Simpanan Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm mt-4">
                        <div>
                            <div class="font-semibold text-gray-700">Simpanan Wajib</div>
                            <div class="text-green-600 font-medium">Rp {{ number_format($totalSimpananWajib, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-gray-500">Dibayar: Rp
                                {{ number_format($totalSimpananWajibDibayar, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-700">Simpanan Sukarela</div>
                            <div class="text-green-600 font-medium">Rp
                                {{ number_format($totalSimpananSukarela, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-500">Dibayar: Rp
                                {{ number_format($totalSimpananSukarelaDibayar, 0, ',', '.') }}</div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-700">Tabungan</div>
                            <div class="text-green-600 font-medium">Rp {{ number_format($totalTabungan, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-gray-500">Dibayar: Rp
                                {{ number_format($totalTabunganDibayar, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart and Activity Row -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mb-6">
            <!-- Grafik Perkembangan -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-lg shadow h-full">
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h6 class="font-semibold text-blue-600">Grafik Perkembangan Koperasi</h6>
                        <span class="px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded-full">Periode: Bulanan</span>
                    </div>
                    <div class="p-6">
                        <div class="h-80">
                            <canvas id="myAreaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-lg shadow h-full">
                    <div class="px-6 py-4 border-b">
                        <h6 class="font-semibold text-blue-600">Aktivitas Terbaru</h6>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-file-invoice-dollar text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-500">10:45 — ID #PJ-00123</div>
                                    <div class="font-semibold text-gray-800">Andi mengajukan pinjaman</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-check text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-xs text-gray-500">09:20 — ID #AG-0045</div>
                                    <div class="font-semibold text-gray-800">Verifikasi anggota baru: Siti</div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-6">
                            <a href="#"
                                class="inline-block px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                                Lihat semua aktivitas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Note Card -->
        <div class="mb-6">
            <div class="bg-white rounded-lg shadow border-l-4 border-yellow-400 p-5">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 text-yellow-400">
                        <i class="fas fa-info-circle text-2xl"></i>
                    </div>
                    <div>
                        <h6 class="font-semibold text-yellow-600 mb-1">Catatan Singkat</h6>
                        <p class="text-gray-700 text-sm">
                            Gunakan panel ini untuk melihat ringkasan bulanan, aktifitas anggota, dan laporan keuangan.
                            Untuk laporan detail gunakan fitur "Buat Laporan".
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <!-- Institusi -->
            <div>
                <div class="bg-white rounded-lg shadow overflow-hidden h-full">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4">
                        <h6 class="font-semibold flex items-center">
                            <i class="fas fa-university mr-2"></i>Institusi
                        </h6>
                    </div>
                    <div class="p-6">
                        <!-- Flex container untuk teks + logo -->
                        <div class="flex items-center mb-4 justify-between">
                            <h5 class="text-xl font-bold text-blue-600">Politeknik Negeri Banyuwangi</h5>
                            <img src="{{ asset('assets/poliwangi_icon.png') }}" alt="Logo Universitas"
                                class="h-10 w-10 object-contain">
                        </div>
                        <div class="mb-3">
                            <div class="font-semibold text-gray-700">Dosen Pembimbing:</div>
                            <p class="text-gray-600">Devit Suwardiyanto, S.Si., M.T</p>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-700">Client:</div>
                            <p class="text-gray-600">Danang Sudarso W.P.J.W, S.P., M.M</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tim Pengembang -->
            <div>
                <div class="bg-white rounded-lg shadow overflow-hidden h-full">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4">
                        <h6 class="font-semibold flex items-center">
                            <i class="fas fa-users-cog mr-2"></i>Tim Pengembang
                        </h6>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-4">
                                <div>
                                    <div class="font-semibold text-gray-800">Moh Naufal</div>
                                    <div class="text-sm text-gray-600">
                                        <i class="fas fa-phone mr-1"></i>+62 896-7580-3596
                                    </div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">Leo Eka Matra</div>
                                    <div class="text-sm text-gray-600">
                                        <i class="fas fa-phone mr-1"></i>+62 831-1495-1853
                                    </div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">Dimas Januar Pradana</div>
                                    <div class="text-sm text-gray-600">
                                        <i class="fas fa-phone mr-1"></i>+62 857-0471-7410
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <div class="font-semibold text-gray-800">Wida Monica Putri</div>
                                    <div class="text-sm text-gray-600">
                                        <i class="fas fa-phone mr-1"></i>+62 812-3581-5523
                                    </div>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">Moh. Eric Ardiansyah</div>
                                    <div class="text-sm text-gray-600">
                                        <i class="fas fa-phone mr-1"></i>+62 838-6285-5152
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright Footer -->
        <div class="text-center py-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                © {{ date('Y') }} Koperasi Poliwangi — Tim Pengembang Politeknik Negeri Banyuwangi
            </p>
        </div>
    </div>

@endsection