<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// Alle routes hier krijgen automatisch het prefix /api
Route::apiResource('users', UsersController::class);
