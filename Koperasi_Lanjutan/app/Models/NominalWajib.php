<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NominalWajib extends Model
{
    protected $table = 'nominal_wajib';
    protected $fillable = ['nominal', 'tahun'];
}
