<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailVerificationLinkController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Coffenfo - Daftar Route
|--------------------------------------------------------------------------
| Urutan file ini:
|   1. Halaman publik
|   2. Autentikasi (peran, login, register, verifikasi)
|   3. Halaman pengguna
|   4. Halaman admin
| Mau menambah halaman baru? Taruh di bagian yang sesuai.
*/


// ===== 1. HALAMAN PUBLIK =====

// Landing page Coffenfo
Route::view('/', 'welcome')->name('home');


// ===== 2. AUTENTIKASI =====

// Pilih peran sebelum login (Pengguna / Mitra / Admin)
Route::view('/pilih-peran', 'auth.role')->name('role.select');

// Login: GET menampilkan formulir, POST memprosesnya
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', function () {
    // SEMENTARA: belum mengecek email/password. Nanti diganti dengan proses login sungguhan.
    return redirect()->route('dashboard');
});

// Register: GET menampilkan formulir, POST memprosesnya (setelah daftar -> halaman login)
Route::view('/register', 'auth.register')->name('register');
Route::post('/register', function () {
    // SEMENTARA: belum menyimpan user ke database.
    return redirect()->route('login');
});

// Tautan dari email (verifikasi email & reset password)
Route::get('/verify-email/{token}', EmailVerificationLinkController::class)
    ->where('token', '[A-Za-z0-9]+')
    ->name('auth.email.verify.link');

Route::get('/reset-password/{token}', PasswordResetLinkController::class)
    ->where('token', '[A-Za-z0-9]+')
    ->name('auth.password.reset.link');


// ===== 3. HALAMAN PENGGUNA =====

Route::view('/dashboard', 'dashboard')->name('dashboard');   // Dashboard / detail cafe
Route::view('/explore',   'explore')->name('explore');       // Eksplorasi & filter cafe
Route::view('/reviews',   'reviews')->name('reviews');       // Review & rating cafe
Route::view('/checkout',  'checkout')->name('checkout');     // Checkout pesanan


// ===== 4. HALAMAN ADMIN =====
// Semua URL diawali /admin dan semua nama diawali "admin."
// Nanti, untuk mengunci halaman admin, cukup tambahkan ->middleware('auth') pada grup ini.
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');  // /admin/dashboard
    Route::view('/moderation', 'admin.moderation')->name('moderation');                  // /admin/moderation
    Route::view('/orders',     'admin.orders')->name('orders');                          // /admin/orders

});