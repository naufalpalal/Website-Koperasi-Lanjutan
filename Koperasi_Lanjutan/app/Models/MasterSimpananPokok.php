<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSimpananPokok extends Model
{
     // Nama tabel (karena bukan jamak)
    protected $table = 'master_simpanan_pokok';

    // Kolom yang dapat diisi
    protected $fillable = [
        'nilai',
        'tahun',
        'bulan',
        'pengurus_id',
    ];

    /**
     * Relasi ke tabel pengurus
     */
    public function pengurus()
    {
        return $this->belongsTo(Pengurus::class, 'pengurus_id');
    }
}
