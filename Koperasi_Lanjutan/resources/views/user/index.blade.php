<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard User Koperasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-blue-100 min-h-screen flex flex-col">
    <!-- Main Content -->
    <main class="flex-1 md:ml-64 p-2 sm:p-4">
        @yield('content')
    </main>


    <!-- Sidebar khusus user -->
    @include('user.layouts.sidebar')
    <!-- JS eksternal -->
     <script src="{{ asset('assets/js/reviewdoc.js') }}"></script>
     <script src="{{ asset('assets/js/simpananwajib_listnomor.js') }}"></script>
</body>

</html>
