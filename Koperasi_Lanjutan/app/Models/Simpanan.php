<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Simpanan extends Model
{
  use HasFactory;


protected $table = 'simpanan';
protected $fillable = ['anggota_id','jenis','nominal','status','mulai_efektif','created_by','updated_by'];


public function anggota(){ return $this->belongsTo(User::class,'anggota_id'); }
}
