<?php

use App\Http\Controllers\UsersController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientTagController;
use App\Http\Controllers\ContractorController;
use Illuminate\Support\Facades\Route;

// Alle routes krijgen automatisch het prefix /api
// kijk in docs endpoints.md voor info guys
Route::apiResource('users', UsersController::class);
Route::apiResource('clients', ClientController::class);
Route::post('client-tags', [ClientTagController::class, 'store']);
Route::apiResource('contractors', ContractorController::class);