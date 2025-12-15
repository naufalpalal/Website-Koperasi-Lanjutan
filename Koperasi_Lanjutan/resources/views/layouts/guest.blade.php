<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota Koperasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#428dff] flex items-center justify-center min-h-screen">
    <div class="relative w-full max-w-3xl mx-auto px-2 py-6">
        @props(['isRegister' => true])
        <!-- Card utama -->
        <div class="rounded-2xl shadow-2xl w-full mx-auto overflow-auto bg-white text-gray-900 max-h-[90vh]">
            <div class="w-full px-6 py-4">
                <!-- Logo di atas form -->
                <div class="flex justify-center">
                    <img src="{{ asset('assets/favicon.png') }}" alt="Logo" class="w-20 h-20">
                </div>
                <!-- Slot konten (form pendaftaran atau login) -->
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('assets/js/register.js') }}"></script>
</html>
