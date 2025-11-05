<?php

namespace App\Models\Pengurus;

use App\Models\Pengurus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterSimpananWajib extends Model
{
    use HasFactory;

    protected $table = 'master_simpanan_wajib';

    protected $fillable = [
        'nilai',
        'tahun',
        'bulan',
        'pengurus_id',
      
    ];

    // relasi ke Pengurus
    public function pengurus()
    {
        return $this->belongsTo(Pengurus::class, 'pengurus_id');
    }
}
