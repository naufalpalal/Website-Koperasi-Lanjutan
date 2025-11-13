@extends ('user.index')

@section('content')
<div class="container mx-auto bg-blue-100 min-h-screen p-6 flex flex-col items-center">
    <!-- Dashboard Title -->
    <div class="text-gray-800 font-bold mb-8 text-2xl">
        Dashboard User
    </div>

    <!-- Grid Menu -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 w-full max-w-5xl mb-8">
        <!-- Simpanan Sukarela -->
        <a href="{{ route('user.simpanan.sukarela.index') }}" 
           class="group bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-50 rounded-lg group-hover:bg-blue-100 transition-colors">
                    <i class="fas fa-piggy-bank text-xl text-blue-600"></i>
                </div>
                <span class="text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </span>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Simpanan Sukarela</h3>
            <p class="text-2xl font-bold text-green-800 mt-2">
            Rp {{ number_format($totalSimpananSukarela, 0, ',', '.') }}
        </p>
        </a>

        <!-- Simpanan Wajib -->
        <a href="{{ route('user.simpanan.wajib.index') }}" 
           class="group bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-emerald-50 rounded-lg group-hover:bg-emerald-100 transition-colors">
                    <i class="fas fa-coins text-xl text-emerald-600"></i>
                </div>
                <span class="text-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </span>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Simpanan Wajib</h3>
            <p class="text-2xl font-bold text-blue-800 mt-2">
            Rp {{ number_format($totalSimpananWajib, 0, ',', '.') }}
        </p>
        </a>

        <!-- Pengajuan Pinjaman -->
        <a href="{{ route('user.pinjaman.create') }}" 
           class="group bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-rose-50 rounded-lg group-hover:bg-rose-100 transition-colors">
                    <i class="fas fa-hand-holding-usd text-xl text-rose-600"></i>
                </div>
                <span class="text-rose-600 opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </span>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Pinjaman</h3>
            <p class="text-2xl font-bold text-yellow-800 mt-2">
            Rp {{ number_format($totalPinjaman, 0, ',', '.') }}
        </p>
        </a>
    </div>

    <!-- Info Card -->
    <a href="{{ route('user.simpanan.tabungan.index') }}" class="block">
    <div class="w-full mx-auto mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-1">Total Tabungan Anda</h3>
                    <p class="text-blue-100 text-sm mb-4">Update terakhir: {{ date('d M Y') }}</p>
                    <div class="text-3xl font-bold">Rp {{ number_format($totalTabungan, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 0V4m0 16v-4"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Informasi Lengkap -->
    <div class="bg-white rounded-2xl p-8 shadow border mb-8 w-full mt-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-8">
            <!-- Logo + Institusi -->
            <div class="md:col-span-4 pr-6 border-r border-gray-200">
                <div class="flex items-start gap-4">
                    <img src="{{ asset('assets/poliwangi_icon.png') }}" alt="Poliwangi Logo" class="w-20 h-20 object-contain rounded-lg bg-gray-50 p-2">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <h4 class="text-md font-semibold text-gray-800">Politeknik Negeri Banyuwangi</h4>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Institusi</p>
                        
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-gray-700"><span class="font-medium text-gray-800">Dosen Pembimbing:</span><br/>Devit Suwardiyanto, S.Si., M.T</p>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <p class="text-sm text-gray-700"><span class="font-medium text-gray-800">Client:</span><br/>Danang Sudarso W.P.J.W, S.P., M.M</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tim Pengembang (tengah) -->
            <div class="md:col-span-4 space-y-4">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h5 class="text-sm font-semibold text-gray-800">Tim Pengembang</h5>
                </div>
                <ul class="space-y-4">
                    <li class="bg-gray-50 p-3 rounded-lg flex items-center gap-3">
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Moh Naufal</div>
                            <div class="text-gray-600 text-sm">+62 896-7580-3596</div>
                        </div>
                    </li>
                    <li class="bg-gray-50 p-3 rounded-lg flex items-center gap-3">
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Leo Eka Matra</div>
                            <div class="text-gray-600 text-sm">+62 858-1548-5484</div>
                        </div>
                    </li>
                    <li class="bg-gray-50 p-3 rounded-lg flex items-center gap-3">
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Dimas Januar Pradana</div>
                            <div class="text-gray-600 text-sm">+62 895-3670-77711</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Tim Pengembang (kanan) -->
            <div class="md:col-span-4 space-y-4 pt-8 md:pt-12">
                <ul class="space-y-4">
                    <li class="bg-gray-50 p-3 rounded-lg flex items-center gap-3">
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Wida Monica Putri</div>
                            <div class="text-gray-600 text-sm">+62 896-7322-0932</div>
                        </div>
                    </li>
                    <li class="bg-gray-50 p-3 rounded-lg flex items-center gap-3">
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-800">Moh. Eric Ardiansyah</div>
                            <div class="text-gray-600 text-sm">+62 851-3660-1435</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-8 pt-6 text-center text-xs text-gray-500 border-t border-gray-200">
            <div class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <p>© {{ date('Y') }} Koperasi Poliwangi — Dibuat oleh Tim Pengembang Politeknik Negeri Banyuwangi</p>
            </div>
        </div>
    </div>
</div>
@endsection
