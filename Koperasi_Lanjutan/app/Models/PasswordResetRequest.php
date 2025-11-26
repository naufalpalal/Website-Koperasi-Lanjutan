<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetRequest extends Model
{
    use HasFactory;

    protected $table = 'password_reset_requests';

    protected $fillable = [
        'user_id',
        'email',
        'token_hash',
        'otp_hash',
        'reset_token',
        'status',
        'expires_at',
        'used_at',
        'ip',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
