<!-- Responsive Sidebar Navigation -->
<button id="sidebarToggle" class="absolute top-4 left-4 z-60 sm:hidden focus:outline-none">
    <svg class="h-5 w-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" />
    </svg>
</button>

<aside id="sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-gray-900 text-white flex flex-col justify-between z-50 shadow-lg transform -translate-x-full sm:translate-x-0 sm:flex transition-transform duration-300
           overflow-y-auto scrollbar-thin scrollbar-thumb-gray-500 hover:scrollbar-thumb-gray-400 scrollbar-track-transparent">

    <div class="flex-1">
        <!-- Logo -->
        <div class="flex items-center px-6 py-5 border-b border-gray-700">
            <img src="{{ asset('assets/favicon.png') }}" alt="Logo" class="h-10 w-10 mr-3">
            <div>
                <span class="text-2xl font-bold">KOPERASI</span>
                <span class="block text-sm font-semibold">Poliwangi</span>
            </div>
        </div>

        <nav class="mt-6 px-2 space-y-2">

            <!-- Dashboard -->
            <a href="{{ route('pengurus.dashboard.index') }}"
                class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                Dashboard
            </a>

            <!-- Hanya untuk SEKRETARIS, ADMIN, KETUA -->
            @if(auth('pengurus')->check() && in_array(auth('pengurus')->user()->role, ['sekretaris', 'superadmin', 'ketua']))
                <div x-data="{ openAnggota: false }" class="space-y-1">
                    <button @click="openAnggota = !openAnggota"
                        class="flex items-center justify-between w-full px-4 py-3 rounded-lg hover:bg-gray-800 transition">
                        <span>Kelola Anggota</span>
                        <svg :class="{ 'rotate-180': openAnggota }" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-width="2" />
                        </svg>
                    </button>
                    <div x-show="openAnggota" x-collapse class="ml-4 space-y-1">
                        <a href="{{ route('pengurus.anggota.verifikasi') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
                            Verifikasi Anggota
                        </a>
                        <a href="{{ route('pengurus.anggota.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
                            Data Anggota
                        </a>
                    </div>
                </div>
            @endif

            <!-- Hanya untuk BENDAHARA, ADMIN, KETUA -->
            @if(auth('pengurus')->check() && in_array(auth('pengurus')->user()->role, ['bendahara', 'superadmin', 'ketua']))
                <div x-data="{ openSimpanan: false }" class="space-y-1">
                    <button @click="openSimpanan = !openSimpanan"
                        class="flex items-center justify-between w-full px-4 py-3 rounded-lg hover:bg-gray-800 transition">
                        <span>Kelola Simpanan</span>
                        <svg :class="{ 'rotate-180': openSimpanan }" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-width="2" />
                        </svg>
                    </button>
                    <div x-show="openSimpanan" x-collapse class="ml-4 space-y-1">
                        <a href="{{ route('pengurus.simpanan.sukarela.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
                            Simpanan Sukarela
                        </a>
                        <a href="{{ route('pengurus.simpanan.wajib_2.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
                            Simpanan Wajib
                        </a>
                        <a href="{{ route('pengurus.tabungan.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
                            Tabungan
                        </a>
                    </div>
                </div>

                <!-- Pinjaman -->
                <a href="{{ route('pengurus.pinjaman.pemotongan') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition">
                    Pinjaman
                </a>
            @endif

            <!-- Hanya untuk ADMIN -->
            @if(auth('pengurus')->check() && auth('pengurus')->user()->role === 'superadmin')
                <a href="{{ route('settings.edit') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition">
                    Pengaturan Koperasi
                </a>
            @endif

        </nav>

        <!-- Logout -->
        <form method="POST" action="{{ route('pengurus.logout') }}" class="mt-6 px-4">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-3 text-left rounded-lg hover:bg-red-700 transition">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Logout
            </button>
        </form>

        <!-- Profil Pengurus -->
        <div class="border-t border-gray-700 px-6 py-4 flex items-center">
            <a href="{{ route('profile.edit') }}" class="flex items-center">
                @php
                    $pengurus = Auth::guard('pengurus')->user();
                @endphp

                <img src="{{ $pengurus && $pengurus->photo_path ? asset('storage/' . $pengurus->photo_path) : asset('assets/default-avatar.png') }}"
                    alt="{{ $pengurus ? $pengurus->name : 'Guest' }}" class="h-10 w-10 rounded-full mr-3">
                <div>
                    <span class="font-semibold">{{ $pengurus ? $pengurus->name : 'Guest' }}</span>
                    <svg class="inline h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M6 9l6 6 6-6" stroke-width="2" />
                    </svg>
                </div>
            </a>
        </div>
    </div>
</aside>

<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden sm:hidden"></div>

<script src="https://unpkg.com/alpinejs" defer></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
