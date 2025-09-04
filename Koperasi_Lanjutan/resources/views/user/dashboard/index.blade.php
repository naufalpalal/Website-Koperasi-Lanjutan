@extends ('user.index')

@section('content')
<div class="container mx-auto bg-blue-100 min-h-screen p-4 sm:p-6 lg:p-8">
    <div class="mb-6">
        <!-- Dashboard Title -->
        <div class="text-gray-700 font-semibold mb-4 text-lg sm:text-xl lg:text-2xl px-2 sm:px-4 lg:ml-10">
            Dashboard user
        </div>

       
        <!-- Riwayat Aktivitas Anggota - Responsive -->
        <div class="mb-6 mt-6 lg:mt-8">
            <div class="bg-gradient-to-r from-blue-400 to-blue-500 rounded-xl shadow-lg p-4 sm:p-5 lg:p-6 text-white">
                <div class="font-semibold mb-3 sm:mb-4 text-base sm:text-lg lg:text-xl">
                    Riwayat Aktivitas Anggota:
                </div>
                
                <!-- Activity Items -->
                <div class="space-y-2 sm:space-y-3">
                    <div class="flex items-start sm:items-center gap-2 sm:gap-3 p-2 sm:p-3 bg-white bg-opacity-10 rounded-lg backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-white flex-shrink-0 mt-0.5 sm:mt-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M16 8l-4.5 4.5L8 10" />
                        </svg>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full">
                            <span class="text-sm sm:text-base font-medium">Andi Mengajukan Pinjaman</span>
                            <span class="text-xs sm:text-sm text-blue-100 mt-1 sm:mt-0">(10:45)</span>
                        </div>
                    </div>
                    
                    <div class="flex items-start sm:items-center gap-2 sm:gap-3 p-2 sm:p-3 bg-white bg-opacity-10 rounded-lg backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-white flex-shrink-0 mt-0.5 sm:mt-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M16 8l-4.5 4.5L8 10" />
                        </svg>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full">
                            <span class="text-sm sm:text-base font-medium">Budi Melakukan Pembayaran</span>
                            <span class="text-xs sm:text-sm text-blue-100 mt-1 sm:mt-0">(09:30)</span>
                        </div>
                    </div>
                    
                    <div class="flex items-start sm:items-center gap-2 sm:gap-3 p-2 sm:p-3 bg-white bg-opacity-10 rounded-lg backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-white flex-shrink-0 mt-0.5 sm:mt-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M16 8l-4.5 4.5L8 10" />
                        </svg>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full">
                            <span class="text-sm sm:text-base font-medium">Citra Mendaftar Sebagai Anggota</span>
                            <span class="text-xs sm:text-sm text-blue-100 mt-1 sm:mt-0">(08:15)</span>
                        </div>
                    </div>
                </div>
                
                <!-- View All Button -->
                <div class="mt-4 sm:mt-6 text-center">
                    <button class="bg-white bg-opacity-20 hover:bg-opacity-30 transition-all duration-200 text-white font-medium py-2 px-4 sm:py-3 sm:px-6 rounded-lg text-sm sm:text-base">
                        Lihat Semua Aktivitas
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection