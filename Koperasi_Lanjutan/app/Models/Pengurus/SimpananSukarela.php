<?php

namespace App\Models\Pengurus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class SimpananSukarela extends Model
{
    use HasFactory;

    protected $table = 'simpanan_sukarela';

    protected $fillable = [
        'nilai',
        'tahun',
        'bulan',
        'status',
        'users_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
