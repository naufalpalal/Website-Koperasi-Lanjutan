<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    // Nama tabelnya
    protected $table = 'password_resets_custom';

    // Kolom yang bisa diisi
    protected $fillable = [
        'user_id',
        'password',
        'status',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
