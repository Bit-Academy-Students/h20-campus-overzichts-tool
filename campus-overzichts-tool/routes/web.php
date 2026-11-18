<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/login', function () {
    return view('loginpage');
})->name('loginpage');

Route::get('/map', function () {
    return view('waterlandmap');
})->name('waterlandmap');
