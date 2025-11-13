<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */
    'guards' => [
        // 🔹 Login untuk anggota (default user)
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // 🔹 Login khusus pengurus
        'pengurus' => [
            'driver' => 'session',
            'provider' => 'pengurus',
        ],

        // Jika kamu nanti punya API juga
        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */
    'providers' => [
        // 🔹 Provider untuk anggota
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // 🔹 Provider untuk pengurus
        'pengurus' => [
            'driver' => 'eloquent',
            'model' => App\Models\Pengurus::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Reset Password Settings
    |--------------------------------------------------------------------------
    */
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        // 🔹 Jika pengurus juga bisa reset password
        'pengurus' => [
            'provider' => 'pengurus',
            'table' => 'password_reset_tokens_pengurus',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
