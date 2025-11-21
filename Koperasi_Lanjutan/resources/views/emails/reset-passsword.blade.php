<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Akun</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 520px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background-color: #2563eb;
            color: #fff;
            text-align: center;
            padding: 20px;
            font-size: 20px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .content p {
            line-height: 1.6;
            margin-bottom: 16px;
        }
        .btn {
            display: inline-block;
            background-color: #2563eb;
            color: #fff !important;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #1e40af;
        }
        .footer {
            background-color: #f3f4f6;
            text-align: center;
            padding: 16px;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Reset Password Akun Koperasi
        </div>
        <div class="content">
            <p>Halo <strong>{{ $name ?? (isset($user) ? $user->nama : 'Pengguna') }}</strong>,</p>

            <p>Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda.</p>

            <p>Untuk melanjutkan proses, silakan klik tombol di bawah ini:</p>

            <p style="text-align: center;">
                <a href="{{ $url }}" class="btn" target="_blank">Reset Password</a>
            </p>

            <p>Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini. Link reset password hanya berlaku selama <strong>60 menit</strong>.</p>

            <p>Salam hangat,<br>
            <strong>Admin Koperasi</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Koperasi. Semua hak dilindungi.
        </div>
    </div>
</body>
</html>
