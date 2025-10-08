<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SimpananPokok extends Model
{
    use HasFactory;

    protected $table = 'simpanan_pokok';

    protected $fillable = [
        'nilai',
        'tahun',
        'bulan',
        'status',
        'users_id',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
