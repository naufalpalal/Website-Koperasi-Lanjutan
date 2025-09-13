<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class wajib extends Model
{

    protected $table = 'wajib';
    protected $fillable = ['amount', 'start_date'];
}
