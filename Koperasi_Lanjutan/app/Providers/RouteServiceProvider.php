<?php
use App\Http\Middleware\RolePengurusMiddleware;
use Illuminate\Support\Facades\Route;

Route::middlewareGroup('role.pengurus', [RolePengurusMiddleware::class]);
