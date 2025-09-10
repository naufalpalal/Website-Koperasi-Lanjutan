<div class="fixed top-0 left-64 right-0 h-16 bg-gray-900 text-white flex items-center justify-between px-6 shadow z-40">
    <div class="flex w-full items-center">
        <!-- Search Input -->
        <input 
            type="text" placeholder="Search" class="w-full bg-zinc-950 text-white text-sm rounded-l-full py-2 px-4 focus:outline-none border border-gray-700 focus:border-blue-500 transition-colors duration-200">
        </div>

        <!-- Foto Profil -->
        <div class="ml-4">
            <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->nama) }}" alt="Foto Profil" class="w-10 h-10 rounded-full border object-cover shadow hover:scale-105 transition duration-200 cursor-default">
        </div>
</div>