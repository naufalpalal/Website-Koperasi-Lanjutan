<?php

namespace App\Models\Pengurus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pinjaman;
use App\Models\User;

class Angsuran extends Model
{
    use HasFactory;

    protected $table = 'angsuran_pinjaman';
    protected $fillable = [
        'pinjaman_id',
        'petugas_id',
        'bulan_ke',
        'jumlah_bayar',
        'tanggal_bayar',
        'status',
        'jenis_pembayaran',
    ];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}

