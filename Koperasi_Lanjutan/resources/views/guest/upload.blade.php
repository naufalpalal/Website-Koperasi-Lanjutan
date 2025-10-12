@extends('guest.index')

@section('content')
<!-- Form Upload -->@php
                $dokumen = \App\Models\Dokumen::where('user_id', auth()->id())->first();
            @endphp
    <div class="container mx-auto bg-blue-100 min-h-screen p-6 flex flex-col items-center justify-center">

        <!-- Pemberitahuan Akun Belum Terverifikasi -->
        <div class="bg-white rounded-xl shadow p-8 text-center max-w-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Akun Anda Belum Terverifikasi</h2>
            <p class="text-gray-600 mb-6">
                Untuk mengaktifkan akun Anda, silakan unggah dokumen yang dibutuhkan di bawah.
            </p>

            @if ($dokumen)
                <div class="mb-6 text-center">
                    <div class="inline-block bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded-lg">
                        ✅ Dokumen sudah diupload.
                    </div>
                    <p class="text-gray-600 mt-2">Anda dapat mengupload ulang untuk mengganti dokumen lama.</p>
                </div>
            @endif
            <form action="{{ route('dokumen.upload') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4 text-left">
                @csrf

                <div>
                    <label for="dokumen_pendaftaran" class="block font-medium text-gray-700 mb-1">Dokumen
                        Pendaftaran</label>
                    <input type="file" name="dokumen_pendaftaran" id="dokumen_pendaftaran" accept="application/pdf" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label for="sk_tenaga_kerja" class="block font-medium text-gray-700 mb-1">SK Tenaga Kerja</label>
                    <input type="file" name="sk_tenaga_kerja" id="sk_tenaga_kerja" accept="application/pdf" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow transition">
                        Upload Dokumen
                    </button>
                </div>
            </form>

            <!-- Instruksi Verifikasi -->
            <div class="text-left text-gray-700 mt-6">
                <h3 class="font-semibold mb-2">Instruksi:</h3>
                <ol class="list-decimal list-inside space-y-1">
                    <li>Download formulir perjanjian kerja / data anggota.</li>
                    <li>Upload kembali dokumen yang sudah ditandatangani melalui form di atas.</li>
                    <li>Tunggu konfirmasi dari pengurus untuk aktivasi akun.</li>
                </ol>
            </div>
        </div>
    </div>
@endsection