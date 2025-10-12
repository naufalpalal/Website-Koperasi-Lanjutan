<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPinjaman extends Model
{
    protected $fillable = ['pinjaman_id', 'file_path'];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }
      public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}