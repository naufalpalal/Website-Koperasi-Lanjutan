<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>

    <!-- Memanggil file CSS -->
    <link rel="stylesheet" href="{{ asset('build/assets/login_admin.css') }}">
    <!-- Memanggil Font Awesome (ikon user & lock) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <h1>Let's <span class="text-primary">Move</span></h1>
        <div class="login-title">Login</div>
        <div class="subtitle">To Continue Your Account</div>

        @if ($errors->any())
            <ul class="text-danger error-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        @if (Session::has('error'))
            <ul class="text-danger error-list">
                <li>{{ Session::get('error') }}</li>
            </ul>
        @endif

        <form action="{{ route('admin.login_submit') }}" method="POST" autocomplete="off">
            @csrf
            <div class="form-group position-relative">
                <span class="input-icon"><i class="fa-solid fa-user"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="form-group position-relative">
                <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <a href="#" class="forgot-link">Forgot Password ?</a>
            <button type="submit" class="btn btn-login">Login</button>
        </form>
    </div>
</body>
</html>
