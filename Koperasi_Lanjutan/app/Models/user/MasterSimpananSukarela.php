<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MasterSimpananSukarela extends Model
{
    protected $table = 'master_simpanan_sukarela';
    protected $fillable = [
        'users_id',
        'nilai',
        'tahun',
        'bulan',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
