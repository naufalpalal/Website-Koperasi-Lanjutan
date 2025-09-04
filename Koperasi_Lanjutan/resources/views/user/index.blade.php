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
    <main class="flex-1 px-8 py-6 md:ml-64">
        <div class="flex-1 p-6">
            @yield('content')
        </div>
    </main>

    <!-- Sidebar khusus user -->
    @include('user.layouts.sidebar')
</body>

</html>
