<?php

namespace App\Models\Pengurus;

use App\Models\Pengurus;
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
        'pengurus_id',
        'users_id',
    ];

    // relasi ke User (anggota yang dipotong gajinya)
    public function pengurus()
    {
        return $this->belongsTo(Pengurus::class, 'pengurus_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
