<!-- Sidebar Navigation -->
<aside class="fixed top-0 left-0 h-full w-64 bg-gray-900 text-white flex flex-col justify-between z-50 shadow-lg">
    <!-- Logo & Title -->
    <div>
        <div class="flex items-center px-6 py-5 border-b border-gray-700">
            <div class="flex items-center mx-4">
                <!-- Example logo icon -->
                <img src="{{ asset('assets/favicon.png') }}" alt="Logo Koperasi" class="h-10 w-10">
            </div>
            <div class="flex flex-col">
                <span class="text-2xl font-bold">KOPERASI</span>
                <span class="text-sm font-semibold">Poliwangi</span>
            </div>
        </div>
        <!-- Menu Items -->
        <nav class="mt-6">
            <a href="#" class="flex items-center px-6 py-3 bg-blue-600 rounded-lg mx-4 mb-2 transition hover:bg-blue-700">
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" stroke-width="2"/>
                </svg>
                Dashboard            </a>
            <a href="#" class="flex items-center px-6 py-3 mx-4 mb-2 rounded-lg transition hover:bg-gray-800">
                <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 4v16m8-8H4" stroke-width="2"/>
                </svg>
                Simpanan
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
</aside>