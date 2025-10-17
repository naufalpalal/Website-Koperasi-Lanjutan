<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body class="bg-[#428dff] flex items-center justify-center min-h-screen">

    <div class="relative w-full max-w-xl flex flex-col items-center justify-center min-h-screen px-2">
        <!-- Logo di luar card -->
        <img src="{{ asset('assets/favicon.png') }}" alt="Logo" class="w-20 h-20 mb-6">

        <div class="relative z-10 w-full">
            @props(['isRegister' => false])

            <!-- Card dengan tema gelap -->
            <div class="rounded-2xl shadow-2xl w-full {{ $isRegister ? 'max-w-4xl' : 'max-w-md' }} mx-auto overflow-hidden bg-gray-900 text-white">
                <div class="w-full px-6 py-6">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </div>
</body>
<script src="{{ asset('assets/js/register.js') }}"></script>
</html>
