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
}

