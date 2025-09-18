<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjamans';
    protected $fillable = [
        'member_id',
        'jumlah',
        'status',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
