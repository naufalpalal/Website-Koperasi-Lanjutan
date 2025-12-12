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
                class="group bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border-l-4 border-blue-500">
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
                <h3 class="text-lg font-semibold text-blue-600 mb-2">Simpanan Sukarela</h3>
                <p class="text-2xl font-bold text-blue-800 mt-2">
                    Rp {{ number_format($totalSimpananSukarela, 0, ',', '.') }}
                </p>
            </a>
            <!-- Simpanan Wajib -->
            <a href="{{ route('user.simpanan.wajib.index') }}"
                class="group bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border-l-4 border-green-500">
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
                <h3 class="text-lg font-semibold text-green-600 mb-2">Simpanan Wajib</h3>
                <p class="text-2xl font-bold text-green-800 mt-2">
                    Rp {{ number_format($totalSimpananWajib, 0, ',', '.') }}
                </p>
            </a>

            <!-- Pengajuan Pinjaman -->
            <a href="{{ route('user.pinjaman.index') }}"
                class="group bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border-l-4 border-rose-500">

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

                <h3 class="text-lg font-semibold text-rose-600 mb-2">Pinjaman</h3>

                <p class="text-sm text-gray-600">Total yang harus dibayar</p>
                <p class="text-2xl font-bold text-rose-800 mb-4">
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

        <a href="{{ route('tabungan.history') }}" class="block w-full max-w-5xl mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white w-full">
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
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full max-w-5xl mb-8">
            <div class="bg-white rounded-lg shadow overflow-hidden h-full flex flex-col">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4">
                    <h6 class="font-semibold flex items-center">
                        <i class="fas fa-university mr-2"></i>Institusi
                    </h6>
                </div>
                <div class="p-6 flex-1">
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

            <div class="bg-white rounded-lg shadow overflow-hidden h-full flex flex-col">
                <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4">
                    <h6 class="font-semibold flex items-center">
                        <i class="fas fa-users-cog mr-2"></i>Tim Pengembang
                    </h6>
                </div>
                <div class="p-6 flex-1">
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

        <div class="text-center py-4 border-t border-gray-200 w-full">
            <p class="text-sm text-gray-600">
                © {{ date('Y') }} Koperasi Poliwangi — Tim Pengembang Politeknik Negeri Banyuwangi
            </p>
        </div>
    </div>
@endsection