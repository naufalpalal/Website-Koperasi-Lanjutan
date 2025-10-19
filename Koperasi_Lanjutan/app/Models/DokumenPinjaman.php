<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPinjaman extends Model
{

    protected $table = 'dokumen_pinjaman';
    protected $fillable = ['pinjaman_id', 'file_path'];

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }
}