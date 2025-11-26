<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $url;

    public function __construct($user, $url)
    {
        $this->user = $user;
        $this->url  = $url;
    }

    public function build()
    {
        return $this->subject("Reset Password Akun Koperasi")
            ->view('emails.reset-passsword') // Sesuai nama file yang ada
            ->with([
                'user' => $this->user,
                'name' => $this->user->nama ?? 'Pengguna',
                'url'  => $this->url
            ]);
    }
}
