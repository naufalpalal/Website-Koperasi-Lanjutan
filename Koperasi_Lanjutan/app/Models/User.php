<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Dokumen;
use App\Models\DokumenPinjaman;
use App\Models\user\SimpananWajib;
use App\Models\Pinjaman;

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
        'username',
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
        // Ambil semua tabungan dengan status diterima atau dipotong
        $tabungans = $this->tabungans()
            ->whereIn('status', ['diterima', 'dipotong'])
            ->get();

        $totalMasuk = $tabungans->sum('nilai');
        $totalKeluar = $tabungans->sum('debit');

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
