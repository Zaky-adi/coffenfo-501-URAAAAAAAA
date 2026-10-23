<?php

use Illuminate\Support\Facades\Route;

// Mengarahkan halaman utama (/) langsung ke view login
Route::get('/', function () {
    return view('auth.login');
});

// Atau jika ingin menggunakan name route:
Route::get('/login', function () {
    return view('auth.login');
})->name('login');