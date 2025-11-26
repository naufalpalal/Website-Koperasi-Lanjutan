<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

use App\Models\Dokumen;
use App\Models\DokumenPinjaman;
use App\Models\user\SimpananWajib;
use App\Models\Pinjaman;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // atribut yang dapat di isi massal
    protected $fillable = [
        'nama',
        'email',
        'no_telepon',
        'password',
        'nip',
        'username',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_rumah',
        'unit_kerja',
        'role',
        'status', // aktif, non-aktif, pending
    ];

    // atribut yang disembunyikan
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
    'terakhir_tidak_aktif' => 'datetime', // <-- tambahkan ini
];

    // RELATIONSHIPS

    public function simpananWajib()
    {
        return $this->hasMany(\App\Models\Pengurus\SimpananWajib::class, 'users_id', 'id');
    }

    public function simpanan()
    {
        return $this->hasMany(\App\Models\Simpanan::class, 'users_id');
    }

    public function simpananSukarela()
    {
        return $this->hasMany(\App\Models\Pengurus\SimpananSukarela::class, 'users_id');
    }

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class, 'user_id');
    }

    public function tabungans()
    {
        return $this->hasMany(\App\Models\Tabungan::class, 'users_id');
    }

    public function angsuran()
    {
        return $this->hasMany(\App\Models\Pengurus\Angsuran::class, 'users_id');
    }

    public function simpananPokok()
    {
        return $this->hasMany(\App\Models\SimpananPokok::class, 'users_id');
    }

    public function totalSaldo()
    {
        $tabungans = $this->tabungans()
            ->whereIn('status', ['diterima', 'dipotong'])
            ->get();

        $totalMasuk = $tabungans->sum('nilai');
        $totalKeluar = $tabungans->sum('debit');

        return $totalMasuk - $totalKeluar;
    }

    public function dokumen()
    {
        return $this->hasOne(Dokumen::class, 'user_id');
    }

    public function dokumenpinjaman()
    {
        return $this->hasOne(DokumenPinjaman::class, 'user_id');
    }

    
    //   Custom email untuk reset password 
    protected static function booted()
    {
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $user = $notifiable;
            $url = url(route('forgot-password.form', [
                'token' => $token,
                'email' => $user->email,
                'nip'   => $user->nip,
            ], false));

            return (new MailMessage)
                ->subject('Reset Password Akun Koperasi')
                ->greeting('Halo ' . $user->nama . ',')
                ->line('Kami menerima permintaan reset password untuk akun Anda.')
                ->action('Reset Password', $url)
                ->line('Jika Anda tidak meminta reset password, abaikan email ini.');
        });
    }
}
