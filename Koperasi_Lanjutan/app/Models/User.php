<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'no_telepon',
        'password',
        'nip',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat_rumah',
        'role',
    ];


    public function simpananWajib()
    {
        return $this->hasMany(\App\Models\Pengurus\SimpananWajib::class, 'users_id');
    }

        public function simpananSukarela()
    {
        return $this->hasMany(\App\Models\Pengurus\SimpananSukarela::class, 'users_id');
    }

    //     public function simpanan()
    // {
    //     return $this->hasMany(\App\Models\Pengurus\Simpanan::class, 'users_id');
    // }

    public function tabungans()
{
    return $this->hasMany(Tabungan::class, 'users_id');
}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'password' => 'hashed',
    //     ];
    // }
}
