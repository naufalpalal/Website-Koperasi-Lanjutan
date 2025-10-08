<?php

namespace App\Models\Pengurus;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SimpananWajib extends Model
{
    use HasFactory;

    protected $table = 'simpanan_wajib';

    protected $fillable = [
        'nilai',
        'tahun',
        'bulan',
        'status',
        'users_id',
    ];

    // relasi ke User (anggota yang dipotong gajinya)
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
