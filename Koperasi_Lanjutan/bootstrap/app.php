<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // alias middleware yang kamu pakai
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'role.pengurus' => \App\Http\Middleware\RolePengurusMiddleware::class,
            'logout.if.authenticated' => \App\Http\Middleware\LogoutIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
