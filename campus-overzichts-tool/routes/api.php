<?php

use App\Http\Controllers\AssignmentClaimController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentTagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Alle routes krijgen automatisch het prefix /api
// kijk in docs endpoints.md voor info guys
Route::apiResource('users', UserController::class);
Route::apiResource('assignments', AssignmentController::class);
Route::apiResource('assignment-claims', AssignmentClaimController::class);
Route::apiResource('assignments.tags', AssignmentTagController::class)->only(['index', 'store', 'destroy']);
