<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Simpanan extends Model
{
    protected $table = 'simpanan'; // <- gunakan tabel 'simpanan'

    protected $fillable = [
        'member_id', 'type', 'amount', 'status', 'note', 'month'
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
