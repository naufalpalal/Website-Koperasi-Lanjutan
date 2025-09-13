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
                <a href="{{ route('admin.dashboard.index') }}"
                    class="flex items-center px-6 py-3 rounded-lg px-4 mb-2 transition hover:bg-blue-700">
                    Dashboard
                </a>
                <a href="{{ route('admin.anggota.index') }}"
                    class="flex items-center px-6 py-3 rounded-lg px-4 mb-2 transition hover:bg-blue-700">
                    Kelola Anggota
                </a>
                <div x-data="{ open: false }">
                    <!-- Menu utama -->
                    <div x-data="{ open: false }">
                        <!-- Menu utama -->
                        <button @click="open = !open"
                            class="flex items-center justify-between w-full px-6 py-3 mb-2 rounded-lg transition hover:bg-gray-800">
                            <span>Simpanan</span>
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transform transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Submenu -->
                        <div x-show="open" x-collapse class="ml-6 mt-1 space-y-1">
                            <a href="{{ route('admin.simpanan.wajib.wajib') }}"
                                class="flex items-center px-6 py-3 mb-2 rounded-lg transition hover:bg-gray-800">
                                Simpanan Wajib
                            </a>
                            <a href="#"
                                class="flex items-center px-6 py-3 mb-2 rounded-lg transition hover:bg-gray-800">
                                Simpanan Sukarela
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('admin.simpanan.kelola.pending') }}"
                        class="flex items-center px-6 py-3 px-4 mb-2 rounded-lg transition hover:bg-gray-800">
                        Pengajuan Simpanan Sukarela
                    </a>
                    <a href="{{ route('admin.pinjaman.index') }}"
                        class="flex items-center px-6 py-3 px-4 mb-2 rounded-lg transition hover:bg-gray-800">
                        Kelola Pinjaman
                    </a>



                    <a href="#"
                        class="flex items-center px-6 py-3 px-4 mb-2 rounded-lg transition hover:bg-gray-800">
                        Pembayaran Pinjaman
                    </a>
                    <!-- <a href="{{ route('admin.pinjaman.index') }}" class="flex items-center px-6 py-3 mx-4 mb-2 rounded-lg transition hover:bg-gray-800">
                    Pinjaman
                </a> -->
                    <a href="{{ route('admin.laporan.index') }}"
                        class="flex items-center px-6 py-3 px-4 mb-2 rounded-lg transition hover:bg-gray-800">
                        Laporan
                    </a>
                    <a href="#"
                        class="flex items-center px-6 py-3 px-4 mb-2 rounded-lg transition hover:bg-gray-800">
                        Kirim Notifikasi
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
    </div>
</aside>

<!-- Overlay (for mobile) -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden sm:hidden"></div>

<!-- Tambahkan Alpine.js untuk scroll panah -->
<script src="https://unpkg.com/alpinejs" defer></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
