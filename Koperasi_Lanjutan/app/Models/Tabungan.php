<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pengurus;

class Tabungan extends Model
{
    use HasFactory;

    protected $table = 'tabungans';

    protected $fillable = [
        'nilai',
        'status',
        'tanggal',
        'users_id',
        'bukti_transfer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
