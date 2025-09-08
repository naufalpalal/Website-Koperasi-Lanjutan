    <div class="flex items-center justify-between mb-8">
        <!-- Search -->
        <div class="flex items-center bg-gray-100 rounded-full px-4 py-2 w-full max-w-md shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2 flex-shrink-0"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <circle cx="11" cy="11" r="8" />
                <path d="M21 21l-4.35-4.35" />
            </svg>
            <input type="text" placeholder="Cari..."
                class="w-full bg-transparent text-sm focus:outline-none text-gray-700" />
        </div>

        <!-- Foto Profil -->
        <div class="ml-4">
            <div>
                <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->nama) }}"
                    alt="Foto Profil"
                    class="w-11 h-11 rounded-full border object-cover shadow hover:scale-105 transition duration-200 cursor-default">
            </div>
        </div>
    </div>