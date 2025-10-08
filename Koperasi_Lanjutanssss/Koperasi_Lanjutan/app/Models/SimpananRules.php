<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimpananRules extends Model
{
    protected $table = 'simpanan_rules';
    protected $fillable = ['type', 'amount', 'start_date', 'end_date'];
}
