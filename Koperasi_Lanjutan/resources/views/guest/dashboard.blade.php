@extends ('guest.index')

@section('content')
    <div class="container mx-auto bg-blue-100 min-h-screen p-6 flex flex-col items-center justify-center">

        <!-- Pemberitahuan Akun Belum Terverifikasi -->
        <div class="bg-white rounded-xl shadow p-8 text-center max-w-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Akun Anda Belum Terverifikasi</h2>
            <p class="text-gray-600 mb-6">
                Untuk mengaktifkan akun Anda, silakan ikuti langkah-langkah verifikasi di bawah.
            </p>

            <!-- Tombol Download dan Upload -->
            <div class="flex justify-center gap-4 mb-6">
                <a href="{{ route('dokumen.download') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg shadow transition">
                    Download Form
                </a>
                <a href="{{ route('dokumen.upload') }}"
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow transition">
                    Upload Form
                </a>
            </div>

            <!-- Instruksi Verifikasi -->
            <div class="text-left text-gray-700">
                <h3 class="font-semibold mb-2">Cara Verifikasi:</h3>
                <ol class="list-decimal list-inside space-y-1">
                    <li>Download formulir perjanjian kerja / data anggota.</li>
                    <li>Isi data diri dengan lengkap dan tandatangani dokumen.</li>
                    <li>Upload kembali dokumen yang sudah ditandatangani melalui tombol Upload di atas.</li>
                    <li>Tunggu konfirmasi dari pengurus untuk aktivasi akun.</li>
                </ol>
            </div>
        </div>
    </div>
@endsection