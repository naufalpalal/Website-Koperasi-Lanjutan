<div x-data="{ sidebarOpen: false }">
    <!-- Toggle Button (visible on mobile) -->
    <button @click="sidebarOpen = !sidebarOpen"
        class="fixed top-4 left-4 z-60 sm:hidden bg-gray-800 p-2 rounded-lg focus:outline-none">
        <!-- Hamburger Icon -->
        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed top-0 left-0 h-full w-64 bg-gray-900 text-white flex flex-col justify-between z-50 shadow-lg 
               sm:translate-x-0 transition-transform duration-300
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
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                            <span>Kelola Anggota</span>
                            <svg :class="{ 'rotate-180': openAnggota }" class="w-4 h-4 transform transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" />
                            </svg>
                        </button>
                        <div x-show="openAnggota" x-collapse class="ml-4 space-y-1">
                            <a href="{{ route('pengurus.anggota.verifikasi') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
                                Verifikasi Anggota
                            </a>
                            <a href="{{ route('pengurus.anggota.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
                                Data Anggota
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Hanya untuk BENDAHARA, ADMIN, KETUA -->
                @if(auth('pengurus')->check() && in_array(auth('pengurus')->user()->role, ['bendahara', 'superadmin', 'ketua']))
                    <div x-data="{ openSimpanan: false }" class="space-y-1">
                        <button @click="openSimpanan = !openSimpanan"
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                            <span>Kelola Simpanan</span>
                            <svg :class="{ 'rotate-180': openSimpanan }" class="w-4 h-4 transform transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" />
                            </svg>
                        </button>
                        <div x-show="openSimpanan" x-collapse class="ml-4 space-y-1">
                            <a href="{{ route('pengurus.simpanan.sukarela.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
                                Simpanan Sukarela
                            </a>
                            <a href="{{ route('pengurus.simpanan.wajib_2.dashboard') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
                                Simpanan Wajib
                            </a>
                            <a href="{{ route('pengurus.tabungan.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
                                Tabungan
                            </a>
                        </div>
                    </div>

                    <!-- Pinjaman -->
                    <a href="{{ route('pengurus.pinjaman.pemotongan') }}"
                        class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                        Pinjaman
                    </a>
                    <!-- <div x-data="{ openSimpanan: false }" class="space-y-1">
                        <button @click="openSimpanan = !openSimpanan"
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg hover:bg-blue-700 transition">
                            <span>setting</span>
                            <svg :class="{ 'rotate-180': openSimpanan }" class="w-4 h-4 transform transition-transform"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" />
                            </svg>
                        </button>
                        <div x-show="openSimpanan" x-collapse class="ml-4 space-y-1">


                            <a href="{{ route('pengurus.simpanan.wajib_2.edit') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
                                edit nominal simpanan wajib
                            </a>
                        </div> 
                    </div>-->

                @endif

                <!-- Hanya untuk ADMIN -->
                @if(auth('pengurus')->check() && auth('pengurus')->user()->role === 'superadmin')
                    <a href="{{ route('pengurus.settings.edit') }}"
                        class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition">
                        Pengaturan Koperasi
                    </a>
                @endif

                 @if(auth('pengurus')->check() && in_array(auth('pengurus')->user()->role, ['bendahara', 'superadmin', 'ketua']))
                    <a href="{{ route('pengurus.settings.edit') }}"
                        class="flex items-center px-4 py-3 rounded-lg hover:bg-gray-800 transition">
                        Laporan Simpan Pinjam
                    </a>
                @endif

            </nav>

            <!-- Logout -->
            <form method="POST" action="{{ route('pengurus.logout') }}" class="mt-6 px-4">
                @csrf
                <button type="submit"
                    class="flex items-center w-full px-4 py-3 text-left rounded-lg hover:bg-red-700 transition">
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

                    @if($pengurus)
                    <img src="{{ $pengurus->photo_path ? asset('storage/' . $pengurus->photo_path) : asset('assets/default-avatar.png') }}"
                        alt="{{ $pengurus->nama }}" class="h-10 w-10 rounded-full object-cover object-center mr-3">

                    <div>
                        <!-- Teks selamat datang & role spesifik -->
                        <p class="text-sm text-gray-400">
                            Selamat datang
                            @if($pengurus->role == 'superadmin')
                                Super Admin
                            @elseif($pengurus->role == 'ketua')
                                Ketua
                            @elseif($pengurus->role == 'bendahara')
                                Bendahara
                            @elseif($pengurus->role == 'sekretaris')
                                Sekretaris
                            @else
                                Pengurus
                            @endif
                        </p>
                    </div>
                    @endif
                </a>
            </div>

        </div>
    </aside>

    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>