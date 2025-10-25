<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $otp;

    /**
     * Buat instance baru.
     */
    public function __construct($user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    /**
     * Bangun email.
     */
    public function build()
    {
        return $this->subject('Kode OTP Reset Password')
                    ->view('emails.send-otp', [
                        'otp' => $this->otp,
                        'user' => $this->user,
                    ]);
    }
}
