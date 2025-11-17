<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanAngsuran extends Model
{
    protected $table = 'pengajuan_angsuran';

    protected $fillable = [
        'user_id',
        'pinjaman_id',
        'angsuran_ids',
        'bukti_transfer',
        'status'
    ];

    protected $casts = [
        'angsuran_ids' => 'array'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Pinjaman
    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'pinjaman_id');
    }
}

