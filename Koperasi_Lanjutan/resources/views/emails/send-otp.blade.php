<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kode OTP Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 20px; color: #333;">
    <div style="max-width: 480px; margin: auto; background: white; border-radius: 8px; padding: 24px; border: 1px solid #ddd;">
        <!-- <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Koperasi" style="height: 60px;">
        </div> -->

        <h2 style="color: #2563eb;">Halo, {{ $user->name }}</h2>
        <p>Kami menerima permintaan untuk mereset password akun Anda.</p>

        <p style="font-size: 18px; text-align: center; font-weight: bold; letter-spacing: 2px; background: #f3f4f6; padding: 12px; border-radius: 6px;">
            Kode OTP Anda: <br>
            <span style="font-size: 24px; color: #2563eb;">{{ $otp }}</span>
        </p>

        <p style="margin-top: 20px;">
            Kode ini berlaku selama <strong>10 menit</strong>.<br>
            Jika Anda tidak meminta reset password, abaikan email ini.
        </p>

        <p style="text-align: center; color: #6b7280; font-size: 12px; margin-top: 30px;">
            © {{ date('Y') }} Koperasi Pegawai — Semua Hak Dilindungi.
        </p>
    </div>
</body>
</html>
