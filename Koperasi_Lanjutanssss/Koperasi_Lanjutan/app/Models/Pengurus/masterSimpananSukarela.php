<?php

namespace App\Models\Pengurus;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class masterSimpananSukarela extends Model
{
    protected $table = "master_simpanan_sukarela";

    protected $fillable = ["nilai", "tahun", "bulan", "users_id"];  


    public function user()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

}
