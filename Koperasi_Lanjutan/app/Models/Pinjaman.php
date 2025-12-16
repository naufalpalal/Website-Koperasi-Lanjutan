<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pengurus\Angsuran;
use App\Models\Pengurus\PinjamanSetting;



class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';

    protected $fillable = [
        'user_id',
        'paket_id',
        'nominal',
        'status',
        'dokumen_verifikasi',
        'bunga',
        'tenor',
        'angsuran',
    ];

    // Relasi ke tabel user (anggota yang mengajukan pinjaman)
    public function dokumen()
    {
        return $this->hasMany(DokumenPinjaman::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);

    }

    public function angsuran()
    {
        return $this->hasMany(Angsuran::class, 'pinjaman_id');
    }
    public function paket()
    {
        return $this->belongsTo(PinjamanSetting::class, 'paket_id');
    }

}
