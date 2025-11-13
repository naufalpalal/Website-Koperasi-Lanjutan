<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentitasKoperasi extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'nama_koperasi',
        'nama_ketua_koperasi',
        'nama_sekretaris_koperasi',
        'nama_bendahara_koperasi',
        'bendahara_gaji',
        'nama_wadir',
    ];
}
