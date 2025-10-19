@extends ('user.index')

@section('content')
<div class="container mx-auto bg-blue-100 min-h-screen p-6 flex flex-col items-center">
    <!-- Dashboard Title -->
    <div class="text-gray-800 font-bold mb-8 text-2xl">
        Dashboard User
    </div>

    <!-- Grid Menu -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 w-full max-w-3xl">
        
        <!-- Simpanan Sukarela -->
        <a href="{{ route('user.simpanan.sukarela.index') }}" 
           class="flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg hover:scale-105 transition transform">
            <div class="w-16 h-16 flex items-center justify-center bg-blue-200 text-blue-700 rounded-full mb-3">
                <i class="fas fa-piggy-bank text-2xl"></i>
            </div>
            <span class="text-gray-700 font-semibold">Simpanan Sukarela</span>
        </a>

        <!-- Simpanan Wajib -->
        <a href="#" 
           class="flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg hover:scale-105 transition transform">
            <div class="w-16 h-16 flex items-center justify-center bg-green-200 text-green-700 rounded-full mb-3">
                <i class="fas fa-coins text-2xl"></i>
            </div>
            <span class="text-gray-700 font-semibold">Simpanan Wajib</span>
        </a>

        <!-- Pengajuan Pinjaman -->
        <a href="#" 
           class="flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg hover:scale-105 transition transform">
            <div class="w-16 h-16 flex items-center justify-center bg-red-200 text-red-700 rounded-full mb-3">
                <i class="fas fa-coins text-2xl"></i>
            </div>
            <span class="text-gray-700 font-semibold">Pengajuan Pinjaman</span>
        </a>
        <div class="container mx-auto p-6">
            <div class="flex justify-center">
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl shadow-lg flex flex-col items-center justify-center aspect-square w-56 hover:shadow-xl transition">
                    <span class="bg-green-500 text-white rounded-full p-3 mt-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 0V4m0 16v-4" />
                        </svg>
                    </span>
                    <div class="mt-6 text-sm text-gray-500">Total Tabungan</div>
                    <div class="font-bold text-2xl text-gray-700 mb-6">
                        Rp {{ number_format($totalTabungan, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Bisa tambah menu lain -->
        <a href="#" 
           class="flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg hover:scale-105 transition transform">
            <div class="w-16 h-16 flex items-center justify-center bg-yellow-200 text-yellow-700 rounded-full mb-3">
                <i class="fas fa-file-alt text-2xl"></i>
            </div>
            <span class="text-gray-700 font-semibold">Laporan</span>
        </a>

    </div>
</div>
@endsection
