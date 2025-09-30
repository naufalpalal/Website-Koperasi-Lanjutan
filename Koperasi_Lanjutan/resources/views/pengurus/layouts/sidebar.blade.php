<!-- Responsive Sidebar Navigation -->
<!-- Toggle Button (visible on mobile) -->
<button id="sidebarToggle" class="absolute top-4 left-4 z-60 sm:hidden focus:outline-none">
    <!-- Hamburger Icon -->
    <svg class="h-5 w-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" />
    </svg>
</button>

<!-- Sidebar -->
<aside id="sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-gray-900 text-white flex-col justify-between z-50 shadow-lg transform -translate-x-full sm:translate-x-0 sm:flex transition-transform duration-300 hidden sm:flex">
    <div class="flex-1 overflow-y-auto">
        <!-- Logo & Title -->
        <div>
            <div class="flex items-center px-6 py-5 border-b border-gray-700">
                <div class="flex items-center mx-4">
                    <img src="{{ asset('assets/favicon.png') }}" alt="Logo Koperasi" class="h-10 w-10">
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl font-bold">KOPERASI</span>
                    <span class="text-sm font-semibold">Poliwangi</span>
                </div>
            </div>
            <!-- Menu Items -->
            <nav class="mt-6">
                <a href="{{ route('pengurus.dashboard.index') }}"
                    class="flex items-center px-6 py-3 rounded-lg px-4 mb-2 transition hover:bg-blue-700">
                    Dashboard
                </a>
                <a href="{{ route('pengurus.KelolaAnggota.index') }}"
                    class="flex items-center px-6 py-3 rounded-lg px-4 mb-2 transition hover:bg-blue-700">
                    Kelola Anggota
                </a>
                <!-- Wrapper -->
                <div x-data="{ openSimpanan: false }" class="space-y-2">

                    <!-- Trigger Dropdown -->
                    <button @click="openSimpanan = !openSimpanan"
                        class="flex items-center justify-between w-full px-6 py-3 mb-2 text-left rounded-lg transition hover:bg-gray-800">
                        <span class="flex items-center">
                            <!-- Icon Folder -->
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7h4l2-2h10a2 2 0 012 2v12a2 2 0 01-2 2H3a2 2 0 01-2-2V9a2 2 0 012-2z" />
                            </svg>
                            Kelola Simpanan
                        </span>
                        <!-- Icon Arrow -->
                        <svg :class="{ 'rotate-180': openSimpanan }"
                            class="w-4 h-4 transform transition-transform text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Items -->
                    <div x-show="openSimpanan" x-collapse class="ml-8 space-y-1">
                        <a href="{{ route('pengurus.simpanan.sukarela.index') }}"
                            class="block px-4 py-2 text-sm rounded-lg transition hover:bg-gray-700 text-gray-300">
                            Simpanan Sukarela
                        </a>
                        {{-- Tambahkan nanti untuk Wajib & Pokok --}}
                         <a href="{{ route('pengurus.simpanan.wajib_2.dashboard') }}" class="block px-4 py-2 text-sm rounded-lg transition hover:bg-gray-700 text-gray-300">Simpanan Wajib</a>
                        {{-- <a href="{{ route('simpanan_pokok.index') }}" class="block px-4 py-2 text-sm rounded-lg transition hover:bg-gray-700 text-gray-300">Simpanan Pokok</a> --}}
                         <a href="{{ route('pengurus.tabungan.index') }}" class="block px-4 py-2 text-sm rounded-lg transition hover:bg-gray-700 text-gray-300">Tabungan</a>


                    </div>
                </div>


                <a href="{{ route('pengurus.simpanan.kelola.pending') }}"
                    class="flex items-center px-6 py-3 px-4 mb-2 rounded-lg transition hover:bg-gray-800">
                    Pengajuan Simpanan Sukarela
                </a>
                <a href="{{ route('pengurus.pinjaman.index') }}"
                    class="flex items-center px-6 py-3 px-4 mb-2 rounded-lg transition hover:bg-gray-800">
                    Kelola Pinjaman
                </a>



                <a href="#" class="flex items-center px-6 py-3 px-4 mb-2 rounded-lg transition hover:bg-gray-800">
                    Pembayaran Pinjaman
                </a>
                <!-- <a href="{{ route('pengurus.pinjaman.index') }}" class="flex items-center px-6 py-3 mx-4 mb-2 rounded-lg transition hover:bg-gray-800">
                    Pinjaman
                </a> -->
                <a href="{{ route('pengurus.laporan.index') }}"
                    class="flex items-center px-6 py-3 px-4 mb-2 rounded-lg transition hover:bg-gray-800">
                    Laporan
                </a>
            </nav>
        </div>
        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit"
                class="flex items-center w-full px-4 sm:px-6 py-3 rounded-lg mx-2 sm:mx-0 text-left transition-colors duration-200 hover:bg-red-700 text-sm sm:text-base">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                <span class="truncate">Logout</span>
            </button>
        </form>

        <!-- User Info -->
        <div class="border-t border-gray-700 px-6 py-4 flex items-center">
            <a href="{{ route('profile.edit') }}" class="flex items-center">
            </a>
        </div>
    </div>
</aside>

<!-- Overlay (for mobile) -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden sm:hidden"></div>

<!-- Tambahkan Alpine.js untuk scroll panah -->
<script src="https://unpkg.com/alpinejs" defer></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
