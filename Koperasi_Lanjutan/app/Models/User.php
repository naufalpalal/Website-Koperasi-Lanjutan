<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Dokumen;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,InteractsWithMedia;

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
        'unit_kerja',
        'role',
        'status', // aktif, non-aktif, pending
    ];


    public function simpananWajib()
    {
        return $this->hasMany(\App\Models\Pengurus\SimpananWajib::class, 'users_id');
    }
    public function simpanan()
    {
        return $this->hasMany(\App\Models\Simpanan::class, 'users_id');
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
     public function registerMediaCollections(): void
    {
        $this->addMediaCollection('dokumen')->useDisk('private');
    }
    
    // app/Models/User.php
public function dokumen()
    {
        return $this->hasOne(Dokumen::class, 'user_id');
    }
    public function dokumenpinjaman()
    {
        return $this->hasOne(DokumenPinjaman::class, 'user_id');
    }

}
