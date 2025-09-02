<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggot extends Model
{
    use HasFactory;

    protected $table = 'anggots';

    protected $fillable = [
        'nama',
        'tgl_lahir',
        'jenis_kelamin',
        'alamat',
        'tgl_masuk',

    ];

    protected $hidden = [
        'password',
    ];
}
