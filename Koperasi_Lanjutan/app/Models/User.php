<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Dokumen;
use App\Models\DokumenPinjaman;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
   protected $fillable = [
    'nama',
    'email',
    'no_telepon',
    'password',
    'nip',
    'tempat_lahir',
    'tanggal_lahir',
    'alamat_rumah',
    'unit_kerja',
    'role',
    'status',// aktif, non-aktif, pending
];

    /**
     * Hidden attributes for serialization
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // RELATIONSHIPS

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

    public function tabungans()
    {
        return $this->hasMany(Tabungan::class, 'users_id');
    }
    public function totalSaldo()
    {
        $totalMasuk = $this->tabungans()
            ->where('status', 'diterima')
            ->sum('nilai');

        $totalKeluar = $this->tabungans()
            ->where('status', 'dipotong')
            ->sum('debit');

        return $totalMasuk - $totalKeluar;
    }

    // RELASI DOKUMEN (tanpa Media Library)
    public function dokumen()
    {
        return $this->hasOne(Dokumen::class, 'user_id');
    }

    public function dokumenpinjaman()
    {
        return $this->hasOne(DokumenPinjaman::class, 'user_id');
    }
}
