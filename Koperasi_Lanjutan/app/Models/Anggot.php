<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggot extends Model
{
    use HasFactory;

    protected $table = 'anggots';

    protected $fillable = [
        'nama'          => 'required|string|max:255',
        'no_telepon'    => 'required|string|max:20',
        'password'      => 'required|string|min:8|confirmed',
        'nip'            => 'required|string|max:20',
        'tempat_lahir'  => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'alamat_rumah'  => 'required|string|max:255',

    ];

    protected $hidden = [
        'password',
    ];
}
