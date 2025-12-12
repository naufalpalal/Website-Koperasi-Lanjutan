<?php

namespace App\Models\Pengurus;

use Illuminate\Database\Eloquent\Model;

class PinjamanSetting extends Model
{
    protected $fillable = [
        'nama_paket',
        'nominal',
        'tenor',
        'bunga',
        'status',
    ];

}
