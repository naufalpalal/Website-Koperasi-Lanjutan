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
            <nav class="mt-4 sm:mt-6">
                <a href="{{ route('user.dashboard.index') }}"
                    class="flex items-center px-4 sm:px-6 py-3 rounded-lg mb-2 mx-2 sm:mx-0 transition-colors duration-200 hover:bg-green-700 text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                    <span class="truncate">Dashboard</span>
                </a>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center w-full px-4 sm:px-6 py-3 rounded-lg mb-2 mx-2 sm:mx-0
            transition-colors duration-200 hover:bg-green-700 text-sm sm:text-base">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5 mr-3 flex-shrink-0"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>
                        <span class="truncate">Simpanan</span>

                        <svg class="w-4 h-4 ml-2 transition-transform duration-200" :class="{ 'rotate-180': open }"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="mt-1">
                        <a href="{{ route('user.simpanan.wajib.index') }}"
                            class="block pl-10 py-2 text-sm hover:bg-green-700 hover:text-white">Simpanan
                            Wajib</a>
                        <a href="{{ route('user.simpanan.sukarela.index') }}"
                            class="block pl-10 py-2 text-sm hover:bg-green-700 hover:text-white">Simpanan Sukarela</a>
                        <a href="{{ route('tabungan.index') }}" 
                            class="block pl-10 py-2 text-sm hover:bg-green-700 hover:text-white">Tabungan</a>
                    </div>
                </div>
                <div>
                    <a href="{{ route('user.pinjaman.create') }}"
                        class="flex items-center px-4 sm:px-6 py-3 rounded-lg mb-2 mx-2 sm:mx-0 transition-colors duration-200 hover:bg-green-700 text-sm sm:text-base">
                        <span class="truncate">Pinjaman</span>
                    </a>
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
            </nav>
        </div>
        <!-- Footer -->
        <div class="border-t border-gray-700 px-6 py-4 flex items-center">
            <a href="{{ route('profile.edit') }}" class="flex items-center">
                <img src="{{ Auth::user()->photo_path ? asset('storage/' . Auth::user()->photo_path) : asset('assets/default-avatar.png') }}" 
                alt="{{ Auth::user()->name }}" 
                class="h-10 w-10 rounded-full mr-3">
                <div>
                    <span class="font-semibold">mdo</span>
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

<!-- Tambahkan Alpine.js untuk scroll panah -->
<script src="https://unpkg.com/alpinejs" defer></script>
<script src="{{ asset('assets/js/script.js') }}"></script>