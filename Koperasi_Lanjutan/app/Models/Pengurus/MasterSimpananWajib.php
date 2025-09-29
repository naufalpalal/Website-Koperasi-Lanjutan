<?php

namespace App\Models\Pengurus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class MasterSimpananWajib extends Model
{
     use HasFactory;

    protected $table = 'master_simpanan_wajib';

    protected $fillable = [
        'nilai',
        'tahun',
        'bulan',
        'users_id',
    ];

    // relasi ke User (pengurus yang update nominal)
    public function user()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

}
