<?php

namespace App\Models\Pengurus;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pinjaman;

class PinjamanSetting extends Model
{
    protected $fillable = [
        'nama_paket',
        'nominal',
        'tenor',
        'bunga',
        'status',
    ];

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class, 'paket_id');
    }

}
