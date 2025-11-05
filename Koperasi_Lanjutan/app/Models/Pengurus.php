<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengurus extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pengurus'; // nama tabel

    /**
     * Kolom yang dapat diisi mass-assignment.
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
        'sk_perjanjian_kerja',
        'photo_path',
        'role', // ketua, bendahara, sekretaris, superadmin
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast atau konversi tipe data otomatis.
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Contoh relasi jika pengurus punya dokumen atau data lain.
     * (opsional, sesuaikan jika nanti ada tabel relasi)
     */
    public function dokumen()
    {
        return $this->hasOne(Dokumen::class, 'pengurus_id');
    }
}
