<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = [
        'user_id',
        'dokumen_pendaftaran',
        'url_dokumen',
        'sk_tenaga_kerja',
    ];
  public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    // app/Models/Dokumen.php


}
