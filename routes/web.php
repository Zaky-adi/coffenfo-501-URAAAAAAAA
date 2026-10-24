<?php

use Illuminate\Support\Facades\Route;

// Mengarahkan halaman utama (/) langsung ke view login
Route::get('/', function () {
    return view('auth.login');
});

// Halaman Login (GET untuk menampilkan halaman)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Proses Login (POST untuk menerima data saat tombol "Masuk" ditekan)
Route::post('/login', function () {
    return redirect()->route('dashboard');
});

// Halaman Register (Daftar)
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Halaman Dashboard / Detail Cafe
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Halaman Eksplorasi / Filter Kafe
Route::get('/explore', function () {
    return view('explore');
})->name('explore');

// Halaman Review & Rating Kafe
Route::get('/reviews', function () {
    return view('reviews');
})->name('reviews');

// Halaman Admin Moderasi Review & Laporan
Route::get('/admin/moderation', function () {
    return view('admin.moderation');
})->name('admin.moderation');