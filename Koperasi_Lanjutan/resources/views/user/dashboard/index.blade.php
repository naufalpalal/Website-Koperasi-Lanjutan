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

<<<<<<< HEAD
=======
        <!-- Pengajuan Pinjaman -->
        <a href="#" 
           class="flex flex-col items-center justify-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg hover:scale-105 transition transform">
            <div class="w-16 h-16 flex items-center justify-center bg-red-200 text-red-700 rounded-full mb-3">
                <i class="fas fa-coins text-2xl"></i>
            </div>
            <span class="text-gray-700 font-semibold">Pengajuan Pinjaman</span>
        </a>

>>>>>>> c2de613c57e45cc7df8f4cac8169dbf6d0e9b2d7
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
