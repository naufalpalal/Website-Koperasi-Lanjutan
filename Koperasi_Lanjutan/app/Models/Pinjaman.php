<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Pinjaman extends Model
{
    protected $table = 'pinjamans';
    protected $fillable = [
        'member_id',
        'jumlah',
        'tenor',
        'cicilan_per_bulan',
        'status',
    ];

    // Relasi ke User
    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
