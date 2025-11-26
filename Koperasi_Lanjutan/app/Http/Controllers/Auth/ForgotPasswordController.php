<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordResetRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    // FORM FORGOT PASSWORD
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // KIRIM LINK RESET PASSWORD
    public function sendResetLink(Request $request)
    {
        try {
            $request->validate([
                'nip'   => 'required',
                'email' => 'required|email' // email hanya untuk pengiriman
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        }

        $user = User::where('nip', $request->nip)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'NIP tidak ditemukan.'
            ], 404);
        }

        // Jika user punya email di database, validasi email harus sesuai
        // Jika user tidak punya email, gunakan email yang diinput
        if ($user->email) {
            // User punya email terdaftar, wajib validasi
            if (strtolower($user->email) !== strtolower($request->email)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak sesuai dengan email terdaftar untuk NIP ini.'
                ], 400);
            }
            $emailToUse = $user->email; // Gunakan email dari database
        } else {
            // User tidak punya email terdaftar, gunakan email yang diinput
            $emailToUse = $request->email;
        }

        // Token plaintext (dikemas ke URL)
        $tokenPlain = Str::random(64);
        $tokenHash = Hash::make($tokenPlain);

        // Hapus request reset password yang masih pending untuk user ini
        PasswordResetRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'token_sent'])
            ->delete();

        // Simpan request reset password dengan tracking lengkap
        $resetRequest = PasswordResetRequest::create([
            'user_id'    => $user->id,
            'email'      => $emailToUse, // Email yang akan digunakan (dari DB jika ada, atau dari input)
            'token_hash' => $tokenHash,
            'otp_hash'   => null, // Set null untuk kolom otp_hash (nullable)
            'reset_token' => null, // Set null untuk kolom reset_token (nullable)
            'status'     => 'token_sent',
            'expires_at' => Carbon::now()->addMinutes(60), // Token berlaku 60 menit
            'ip'         => $request->ip(),
        ]);

        $url = url('/forgot-password/' . $tokenPlain . '?nip=' . $user->nip);

        // Kirim email ke email yang ditentukan
        try {
            Mail::to($emailToUse)->send(new ResetPasswordMail($user, $url));
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Failed to send reset password email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $emailToUse,
                'user_id' => $user->id
            ]);
            
            // Untuk development, tampilkan error detail
            $errorMessage = config('app.debug') 
                ? 'Gagal mengirim email: ' . $e->getMessage()
                : 'Gagal mengirim email. Pastikan konfigurasi email sudah benar atau hubungi administrator.';
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Link reset password telah dikirim ke email Anda.'
        ]);
    }


    // TAMPILKAN FORM RESET PASSWORD
    public function showResetForm($token)
    {
        return view('auth.forgot-password', [
            'token' => $token,
            'nip' => request()->query('nip')
        ]);
    }


    // RESET PASSWORD
    public function resetPassword(Request $request)
    {
        $request->validate([
            'nip'      => 'required',
            'password' => 'required|min:8|confirmed',
            'token'    => 'required'
        ]);

        $user = User::where('nip', $request->nip)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'NIP tidak ditemukan.'
            ], 404);
        }

        // Cari request reset password untuk user ini
        $resetRequest = PasswordResetRequest::where('user_id', $user->id)
            ->whereIn('status', ['token_sent', 'pending'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$resetRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan atau sudah digunakan.'
            ], 400);
        }

        // Cek kecocokan token
        if (!Hash::check($request->token, $resetRequest->token_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.'
            ], 400);
        }

        // Cek apakah token sudah expired
        if ($resetRequest->expires_at && Carbon::now()->isAfter($resetRequest->expires_at)) {
            $resetRequest->update(['status' => 'expired']);
            return response()->json([
                'success' => false,
                'message' => 'Token sudah kedaluwarsa.'
            ], 400);
        }

        // Cek apakah token sudah digunakan
        if ($resetRequest->status === 'used' || $resetRequest->used_at) {
            return response()->json([
                'success' => false,
                'message' => 'Token sudah digunakan.'
            ], 400);
        }

        // Update password user
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Update status request menjadi used dan catat waktu penggunaan
        $resetRequest->update([
            'status'   => 'used',
            'used_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset!'
        ]);
    }
}
