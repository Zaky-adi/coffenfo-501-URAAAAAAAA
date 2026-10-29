<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailVerificationLinkController;

Route::get('/verify-email/{token}', EmailVerificationLinkController::class)
    ->where('token', '[A-Za-z0-9]+')
    ->name('auth.email.verify.link');

// 1. Halaman Utama (/) menampilkan Landing Page Coffenfo
Route::get('/', function () {
    return view('welcome');
});

// 2. Halaman Login (GET untuk menampilkan formulir login)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// 3. Proses Login (POST - Saat pengguna masuk, diarahkan ke Dashboard)
Route::post('/login', function () {
    return redirect()->route('dashboard');
});

// 4. Halaman Register (GET untuk menampilkan formulir pendaftaran)
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// 5. Proses Register (POST - Setelah berhasil daftar, DIARAHKAN KE HALAMAN LOGIN)
Route::post('/register', function () {
    return redirect()->route('login');
});

// 6. Halaman Dashboard / Detail Cafe
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// 7. Halaman Eksplorasi / Filter Kafe
Route::get('/explore', function () {
    return view('explore');
})->name('explore');

// 8. Halaman Review & Rating Kafe
Route::get('/reviews', function () {
    return view('reviews');
})->name('reviews');

// 9. Halaman Admin Moderasi Review & Laporan
Route::get('/admin/moderation', function () {
    return view('admin.moderation');
})->name('admin.moderation');

// 10. Halaman Checkout Pesanan
Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

// 11. Halaman Kelola Pesanan Masuk Admin
Route::get('/admin/orders', function () {
    return view('admin.orders');
})->name('admin.orders');