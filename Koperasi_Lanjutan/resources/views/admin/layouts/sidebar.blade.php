<!-- Responsive Sidebar Navigation -->
<!-- Toggle Button (visible on mobile) -->
<button id="sidebarToggle" 
    class="absolute top-4 left-4 z-60 sm:hidden focus:outline-none">
    <!-- Hamburger Icon -->
    <svg class="h-5 w-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M4 6h16M4 12h16M4 18h16" stroke-width="2"/>
    </svg>
</button>

<!-- Sidebar -->
<aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-gray-900 text-white flex-col justify-between z-50 shadow-lg transform -translate-x-full sm:translate-x-0 sm:flex transition-transform duration-300 hidden sm:flex">
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
                <a href="#" class="flex items-center px-6 py-3 rounded-lg mx-4 mb-2 transition hover:bg-blue-700">
                    Dashboard
                </a>
                <a href="#" class="flex items-center px-6 py-3 mx-4 mb-2 rounded-lg transition hover:bg-gray-800">
                    Kelola Anggota
                </a>
                <!-- Dropdown Simpan Pinjam -->
                <div x-data="{ open: false, simpananOpen: false }" class="w-full">
                    <!-- Tombol Simpan Pinjam -->
                    <button @click="open = !open" 
                        class="flex items-center justify-between w-full px-6 py-3 px-4 mb-2 rounded-lg transition hover:bg-gray-800">
                        <span class="flex items-center">
                            Simpan Pinjam
                        </span>
                        <!-- Panah -->
                        <svg :class="{'rotate-180': open}" 
                            class="h-4 w-4 transform transition-transform" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <!-- Sub menu -->
                    <div x-show="open" x-transition class="ml-12 space-y-2">
                        <!-- Simpanan dengan nested dropdown -->
                        <button @click="simpananOpen = !simpananOpen" 
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg justify-between">
                            <span>Simpanan</span>
                            <!-- Panah kecil untuk simpanan -->
                            <svg :class="{'rotate-180': simpananOpen}" 
                                class="h-4 w-4 transform transition-transform" 
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <!-- Dropdown Simpanan -->
                        <div x-show="simpananOpen" x-transition class="ml-6 space-y-2">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg">
                                Simpanan Wajib
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg">
                                Simpanan Sukarela
                            </a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg">
                                Simpanan Pokok
                            </a>
                        </div>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 rounded-lg">
                            Pinjaman
                        </a>
                    </div>
                </div>
                <a href="#" class="flex items-center px-6 py-3 mx-4 mb-2 rounded-lg transition hover:bg-gray-800">
                    Pembayaran Pinjaman
                </a>
                <a href="#" class="flex items-center px-6 py-3 mx-4 mb-2 rounded-lg transition hover:bg-gray-800">
                    Pengajuan Pinjaman
                </a>
                <a href="#" class="flex items-center px-6 py-3 mx-4 mb-2 rounded-lg transition hover:bg-gray-800">
                    Laporan
                </a>
                <a href="#" class="flex items-center px-6 py-3 mx-4 mb-2 rounded-lg transition hover:bg-gray-800">
                    Kirim Notifikasi
                </a>
            </nav>
        </div>
        <!-- User Info -->
       <div class="border-t border-gray-700 px-6 py-4 flex items-center">
            <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="User" class="h-10 w-10 rounded-full mr-3">
            <div>
                <span class="font-semibold">mdo</span>
                <svg class="inline h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M6 9l6 6 6-6" stroke-width="2"/>
                </svg>
            </div>
        </div>
    </div>
</aside>

<!-- Overlay (for mobile) -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden sm:hidden"></div>

<!-- Tambahkan Alpine.js untuk scroll panah -->
<script src="https://unpkg.com/alpinejs" defer></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
