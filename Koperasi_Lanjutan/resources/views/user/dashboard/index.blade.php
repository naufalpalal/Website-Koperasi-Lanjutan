@extends ('user.index')

@section('content')
    <div class="container mx-auto bg-blue-100 min-h-screen p-6 flex flex-col items-center">
        <!-- Dashboard Title -->
        <div class="text-gray-800 font-bold mb-8 text-2xl">
            Dashboard Anggota {{ Auth::user()->nama }}
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </span>
                </div>

                <h3 class="text-lg font-semibold text-gray-800 mb-2">Pinjaman</h3>

                <p class="text-sm text-gray-600">Total yang harus dibayar</p>
                <p class="text-2xl font-bold text-yellow-800 mb-4">
                    Rp {{ number_format($totalBayar, 0, ',', '.') }}
                </p>

                <p class="text-sm font-semibold mt-2">
                    Angsuran bulan ini:
                    @if ($statusAngsuranBulanIni === 'lunas')
                        <span class="text-green-600">Lunas ✔</span>
                    @else
                        <span class="text-red-600">Belum Lunas ✘</span>
                    @endif
                </p>

            </a>

        </div>

        <!-- Info Card -->
        <a href="{{ route('tabungan.history') }}" class="block">
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
                                    d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 0V4m0 16v-4" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Informasi Lengkap (compact) -->
            <div class="bg-white rounded-xl p-4 shadow-sm border mb-8 w-full mt-8">
                <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
                    <!-- Logo + Institusi (compact) -->
                    <div class="md:col-span-4 pr-4 border-r border-gray-100">
                        <div class="flex items-start gap-3">
                            <img src="{{ asset('assets/poliwangi_icon.png') }}" alt="Poliwangi Logo"
                                class="w-16 h-16 object-contain rounded bg-gray-50 p-1">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-800">Politeknik Negeri Banyuwangi</h4>
                                <p class="text-xs text-gray-600 mb-2">Institusi</p>
                                <p class="text-xs text-gray-700 mb-1"><span class="font-medium text-gray-800">Dosen
                                        Pembimbing:</span> Devit Suwardiyanto, S.Si., M.T</p>
                                <p class="text-xs text-gray-700"><span class="font-medium text-gray-800">Client:</span>
                                    Danang Sudarso W.P.J.W, S.P., M.M</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tim Pengembang (compact) -->
                    <div class="md:col-span-4 space-y-2">
                        <h5 class="text-sm font-semibold text-gray-800 mb-2">Tim Pengembang</h5>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li class="flex items-center gap-3">
                                <div class="bg-gray-50 p-2 rounded-full w-8 h-8 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800 text-sm">Moh Naufal</div>
                                    <div class="text-gray-600 text-xs">+62 896-7580-3596</div>
                                </div>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="bg-gray-50 p-2 rounded-full w-8 h-8 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800 text-sm">Leo Eka Matra</div>
                                    <div class="text-gray-600 text-xs">+62 831-1495-1853</div>
                                </div>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="bg-gray-50 p-2 rounded-full w-8 h-8 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800 text-sm">Dimas Januar Pradana</div>
                                    <div class="text-gray-600 text-xs">+62 857-0471-7410</div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Tim Pengembang (kanan, compact) -->
                    <div class="md:col-span-4 space-y-2">
                        <!-- spacer heading supaya alignment sama dengan kolom tengah -->
                        <h5 class="text-sm font-semibold text-gray-800 mb-2 invisible">Tim Pengembang</h5>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li class="flex items-center gap-3">
                                <div class="bg-gray-50 p-2 rounded-full w-8 h-8 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800 text-sm">Wida Monica Putri</div>
                                    <div class="text-gray-600 text-xs">+62 812-3581-5523</div>
                                </div>
                            </li>
                            <li class="flex items-center gap-3">
                                <div class="bg-gray-50 p-2 rounded-full w-8 h-8 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800 text-sm">Moh. Eric Ardiansyah</div>
                                    <div class="text-gray-600 text-xs">+62 838-6285-5152</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Copyright (compact) -->
                <div class="mt-6 text-center text-xs text-gray-500 border-t border-gray-100 pt-3">
                    <p>© {{ date('Y') }} Koperasi Poliwangi — Tim Pengembang Politeknik Negeri Banyuwangi</p>
                </div>
            </div>
    </div>
@endsection
