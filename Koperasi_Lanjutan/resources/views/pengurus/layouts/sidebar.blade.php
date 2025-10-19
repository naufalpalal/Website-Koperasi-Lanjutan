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
    class="fixed top-0 left-0 h-full w-64 bg-gray-900 text-white flex-col justify-between z-50 shadow-lg transform -translate-x-full sm:translate-x-0 sm:flex transition-transform duration-300 hidden sm:flex
           overflow-y-auto scrollbar-thin scrollbar-thumb-gray-500 hover:scrollbar-thumb-gray-400 scrollbar-track-transparent">
    <div class="flex-1">
        <!-- Logo & Title -->
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
        <nav class="mt-6 px-2">
            <a href="{{ route('pengurus.dashboard.index') }}"
                class="flex items-center px-4 py-3 rounded-lg mb-2 transition hover:bg-blue-700">
                Dashboard
            </a>
            <!-- Kelola Anggota Dropdown -->
            <div x-data="{ openAnggota: false }" class="space-y-2">
                <button @click="openAnggota = !openAnggota"
                    class="flex items-center justify-between w-full px-4 py-3 mb-2 text-left rounded-lg transition hover:bg-gray-800">
                    <span>Kelola Anggota</span>
                    <svg :class="{ 'rotate-180': openAnggota }"
                        class="w-4 h-4 transform transition-transform text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="openAnggota" x-collapse class="ml-4 space-y-1">
                    <a href="{{ route('pengurus.KelolaAnggota.verifikasi') }}"
                        class="block px-4 py-2 text-sm rounded-lg transition hover:bg-gray-700 text-gray-300">
                        Verifikasi Anggota
                    </a>
                    <a href="{{ route('pengurus.KelolaAnggota.index') }}"
                        class="block px-4 py-2 text-sm rounded-lg transition hover:bg-gray-700 text-gray-300">
                        Data Anggota
                    </a>
                </div>
            </div>

            <!-- Kelola Simpanan Dropdown -->
            <div x-data="{ openSimpanan: false }" class="space-y-2">
                <button @click="openSimpanan = !openSimpanan"
                    class="flex items-center justify-between w-full px-4 py-3 mb-2 text-left rounded-lg transition hover:bg-gray-800">
                    <span>Kelola Simpanan</span>
                    <svg :class="{ 'rotate-180': openSimpanan }"
                        class="w-4 h-4 transform transition-transform text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="openSimpanan" x-collapse class="ml-4 space-y-1">
                    <a href="{{ route('pengurus.simpanan.sukarela.index') }}"
                        class="block px-4 py-2 text-sm rounded-lg transition hover:bg-gray-700 text-gray-300">
                        Simpanan Sukarela
                    </a>
                    <a href="{{ route('pengurus.simpanan.wajib_2.dashboard') }}"
                        class="block px-4 py-2 text-sm rounded-lg transition hover:bg-gray-700 text-gray-300">
                        Simpanan Wajib
                    </a>
                </div>
            </div>
            <a href="{{ route('pengurus.pinjaman.index') }}"
                class="flex items-center px-4 py-3 mb-2 rounded-lg transition hover:bg-gray-800">
                Pinjaman
            </a>
            <a href="{{ route('settings.edit') }}"
                        class="block px-4 py-2 text-sm rounded-lg transition hover:bg-gray-700">
                        Setting
                    </a>
            <a href="{{ route('pengurus.tabungan.index') }}"
                        class="block px-4 py-2 text-sm rounded-lg transition hover:bg-gray-700">
                        Tabungan
                    </a>

            <!-- <a href="{{ route('pengurus.pinjaman.index') }}"
                class="flex items-center px-4 py-3 mb-2 rounded-lg transition hover:bg-gray-800">
                Kelola Pinjaman
            </a>
            <a href="#"
                class="flex items-center px-4 py-3 mb-2 rounded-lg transition hover:bg-gray-800">
                Pembayaran Pinjaman
            </a>
            <a href="{{ route('pengurus.laporan.index') }}"
                class="flex items-center px-4 py-3 mb-2 rounded-lg transition hover:bg-gray-800">
                Laporan
            </a> -->
        </nav>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="mt-4 px-4">
            @csrf
            <button type="submit"
                class="flex items-center w-full px-4 py-3 rounded-lg text-left transition hover:bg-red-700 text-sm">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                Logout
            </button>
        </form>
        <div class="border-t border-gray-700 px-6 py-4 flex items-center">
            <a href="{{ route('profile.edit') }}" class="flex items-center">
                <img src="{{ Auth::user()->photo_path ? asset('storage/' . Auth::user()->photo_path) : asset('assets/default-avatar.png') }}"
                    alt="{{ Auth::user()->name }}" class="h-10 w-10 rounded-full mr-3">
                <div>
                    <span class="font-semibold">{{ Auth::user()->name }}</span>
                    <svg class="inline h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M6 9l6 6 6-6" stroke-width="2" />
                    </svg>
                </div>
            </a>
        </div>
    </div>
</aside>

<!-- Overlay (for mobile) -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden sm:hidden"></div>

<!-- Alpine.js -->
<script src="https://unpkg.com/alpinejs" defer></script>
<script src="{{ asset('assets/js/script.js') }}"></script>