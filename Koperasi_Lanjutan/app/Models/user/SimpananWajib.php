<?php

namespace App\Models\user;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SimpananWajib extends Model
{
    use HasFactory;

    protected $table = 'simpanan_wajib';

    protected $fillable = [
        'nilai', 'tahun', 'bulan', 'status', 'users_id', 'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
