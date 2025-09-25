<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Koperasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>

<body class="bg-blue-100 min-h-screen flex flex-col">
    <!-- Main Content -->
    <main class="flex-1 px-8 py-6 md:ml-64">
        <div class="flex-1 p-6">
            @yield('content')
        </div>
    </main>

    <!-- Footer Navigation -->
    @include('pengurus.layouts.sidebar')

    <!-- JS eksternal -->
    <script src="{{ asset('assets/js/simpanan.js') }}"></script>
</body>

</html>
