<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SendOtpMail;

class PasswordResetRequestController extends Controller
{
    /**
     * Step 1: Kirim OTP ke email user
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'email' => 'required|email',
        ]);

        // Rate limit 5 percobaan / 15 menit
        $key = 'password-reset-request:' . $request->nip . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'status' => false,
                'message' => 'Terlalu banyak percobaan. Coba lagi nanti.'
            ], 429);
        }
        RateLimiter::hit($key, 60 * 15);

        // Cek user berdasarkan NIP
        $user = User::where('nip', $request->nip)->first();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'NIP tidak ditemukan.'], 404);
        }

        // Gunakan email dari input user (bukan dari tabel users)
        $email = strtolower(trim($request->email));

        // Generate OTP dan simpan hash-nya
        $otp = random_int(100000, 999999);
        $otpHash = Hash::make((string) $otp);
        $expiresAt = now()->addMinutes(10);

        $reset = PasswordResetRequest::create([
            'user_id'     => $user->id,
            'email'       => $email,
            'otp_hash'    => $otpHash,
            'reset_token' => null,
            'expires_at'  => $expiresAt,
            'status'      => 'otp_sent',
            'ip'          => $request->ip(),
        ]);

        try {
            Mail::to($email)->send(new SendOtpMail($user, $otp));
            Log::info("OTP {$otp} sent to {$email} for NIP {$user->nip}");

            return response()->json([
                'status'  => true,
                'message' => 'Kode OTP telah dikirim ke email Anda.',
                'request_id' => $reset->id
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal kirim OTP: ' . $e->getMessage());
            $reset->delete();

            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengirim OTP ke email. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Step 2: Verifikasi OTP dari user
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'email' => 'required|email',
            'otp' => 'required|string|digits:6',
        ]);

        $user = User::where('nip', $request->nip)->first();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'NIP tidak ditemukan.'], 404);
        }

        $otpRecord = PasswordResetRequest::where('user_id', $user->id)
            ->where('email', strtolower(trim($request->email)))
            ->where('status', 'otp_sent')
            ->latest()
            ->first();

        if (!$otpRecord) {
            return response()->json(['status' => false, 'message' => 'OTP tidak ditemukan.'], 404);
        }

        // Expired
        if (now()->gt($otpRecord->expires_at)) {
            $otpRecord->delete();
            return response()->json(['status' => false, 'message' => 'OTP telah kadaluarsa.'], 400);
        }

        // Cek OTP
        if (!Hash::check($request->otp, $otpRecord->otp_hash)) {
            return response()->json(['status' => false, 'message' => 'OTP salah.'], 400);
        }

        // OTP valid, buat token baru untuk reset password
        $resetToken = bin2hex(random_bytes(32));
        $otpRecord->update([
            'status' => 'otp_verified',
            'reset_token' => Hash::make($resetToken),
            'expires_at' => now()->addMinutes(60), // token berlaku 1 jam
        ]);

        return response()->json([
            'status' => true,
            'message' => 'OTP berhasil diverifikasi.',
            'reset_token' => $resetToken,
            'nip' => $request->nip,
            'email' => strtolower(trim($request->email))
        ]);
    }

    /**
     * Step 3: Reset password setelah OTP terverifikasi
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'email' => 'required|email',
            'reset_token' => 'required|string',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('nip', $request->nip)->first();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        $resetRecord = PasswordResetRequest::where('user_id', $user->id)
            ->where('email', strtolower(trim($request->email)))
            ->where('status', 'otp_verified')
            ->latest()
            ->first();

        if (!$resetRecord || !Hash::check($request->reset_token, $resetRecord->reset_token)) {
            return response()->json(['status' => false, 'message' => 'Token reset tidak valid.'], 400);
        }

        if (now()->gt($resetRecord->expires_at)) {
            $resetRecord->delete();
            return response()->json(['status' => false, 'message' => 'Token reset telah kadaluarsa.'], 400);
        }

        // Simpan password baru
        $user->update(['password' => Hash::make($request->password)]);

        // Tandai selesai
        $resetRecord->update([
            'status' => 'completed',
            'used_at' => now()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password berhasil direset. Silakan login kembali.'
        ]);
    }

    // (Opsional: admin approval)
    public function approve($id)
    {
        $resetRequest = PasswordResetRequest::findOrFail($id);
        $user = $resetRequest->user;
        $user->password = $resetRequest->password;
        $user->save();
        $resetRequest->update(['status' => 'approved']);

        return back()->with('success', 'Password berhasil direset.');
    }

    public function reject($id)
    {
        $resetRequest = PasswordResetRequest::findOrFail($id);
        $resetRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Permintaan reset password ditolak.');
    }
}
