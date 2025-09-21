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
        'member_id',
        'amount',
        'status',
        'alasan',
        'periode',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
