<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// Alle routes krijgen automatisch het prefix /api
// kijk in docs endpoints.md voor info guys
Route::apiResource('users', UsersController::class);
