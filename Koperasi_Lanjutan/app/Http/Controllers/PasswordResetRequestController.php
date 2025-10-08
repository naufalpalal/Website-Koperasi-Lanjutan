<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PasswordResetRequestController extends Controller
{
    // Kirim OTP ke email anggota (fase 1)
    public function sendOtp(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'nip'  => 'required|string',
        ]);

        $key = 'password-reset-request:' . $request->nip . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['too_many' => 'Terlalu banyak percobaan. Coba lagi nanti.']);
        }
        RateLimiter::hit($key, 60 * 15); // blok 15 menit setelah limit

        $user = User::where('nama', $request->nama)
            ->where('nip', $request->nip)
            ->first();

        if (! $user || empty($user->email)) {
            return back()->withErrors(['not_found' => 'Data anggota tidak ditemukan atau email tidak tersedia.']);
        }

        $otp = random_int(100000, 999999);
        $otpHash = Hash::make((string)$otp);
        $expiresAt = Carbon::now()->addMinutes(10);

        // Simpan request OTP sebagai audit (jangan simpan OTP plain)
        $reset = PasswordResetRequest::create([
            'user_id'    => $user->id,
            'password'   => null,
            'otp_hash'   => $otpHash,
            'expires_at' => $expiresAt,
            'status'     => 'otp_sent',
            'ip'         => $request->ip(),
        ]);

        // Kirim OTP via email (sederhana). Ganti dengan Mailable sesuai kebutuhan.
        try {
            Mail::raw("Kode OTP Anda: {$otp}. Berlaku 10 menit.", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('OTP Reset Password - Koperasi');
            });
        } catch (\Exception $e) {
            // bersihkan record jika pengiriman gagal
            $reset->delete();
            return back()->withErrors(['mail' => 'Gagal mengirim OTP. Periksa konfigurasi mail.']);
        }

        return back()->with('success', 'OTP telah dikirim ke email terdaftar. Masukkan OTP untuk melanjutkan.')
            ->with('request_id', $reset->id);
    }

    // Verifikasi OTP + set password baru (fase 2)
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'request_id' => 'required|integer|exists:password_reset_requests,id',
            'otp'        => 'required|digits:6',
            'password'   => 'required|string|min:6|confirmed',
        ]);

        $resetRequest = PasswordResetRequest::findOrFail($request->request_id);

        if ($resetRequest->status !== 'otp_sent') {
            return back()->withErrors(['invalid' => 'Permintaan tidak valid atau sudah diproses.']);
        }

        if (Carbon::now()->greaterThan($resetRequest->expires_at)) {
            $resetRequest->update(['status' => 'expired']);
            return back()->withErrors(['expired' => 'OTP telah kadaluarsa. Silakan minta ulang.']);
        }

        $verifyKey = 'password-reset-verify:' . $resetRequest->id . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($verifyKey, 5)) {
            return back()->withErrors(['too_many' => 'Terlalu banyak percobaan verifikasi. Coba lagi nanti.']);
        }

        if (! Hash::check((string)$request->otp, $resetRequest->otp_hash)) {
            RateLimiter::hit($verifyKey, 60 * 15);
            return back()->withErrors(['otp' => 'OTP tidak sesuai.']);
        }

        // OTP cocok: ganti password secara atomik dan tandai completed
        DB::transaction(function () use ($resetRequest, $request) {
            $user = User::findOrFail($resetRequest->user_id);
            $user->password = Hash::make($request->password);
            $user->save();

            $resetRequest->update([
                'status'     => 'completed',
                'used_at'    => Carbon::now(),
                'otp_hash'   => null, // bersihkan hash OTP
            ]);
        });

        RateLimiter::clear($verifyKey);

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }

    // Pengurus setujui reset (opsional tetap dipertahankan)
    public function approve($id)
    {
        $resetRequest = PasswordResetRequest::findOrFail($id);

        $user = $resetRequest->user;
        $user->password = $resetRequest->password; // jika flow lama menyimpan hash
        $user->save();

        $resetRequest->update(['status' => 'approved']);

        return back()->with('success', 'Password berhasil direset.');
    }

    // Pengurus tolak reset
    public function reject($id)
    {
        $resetRequest = PasswordResetRequest::findOrFail($id);
        $resetRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Permintaan reset password ditolak.');
    }
}
