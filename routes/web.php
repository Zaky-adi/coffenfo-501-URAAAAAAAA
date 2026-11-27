<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailVerificationLinkController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Coffenfo - Daftar Route
|--------------------------------------------------------------------------
| Urutan file ini:
|   1. Halaman publik
|   2. Autentikasi (peran, login, register, verifikasi)
|   3. Halaman pengguna
|   4. Halaman admin (login admin + panel admin)
| Mau menambah halaman baru? Taruh di bagian yang sesuai.
*/


// ===== 1. HALAMAN PUBLIK =====

// Landing page Coffenfo
Route::view('/', 'welcome')->name('home');

// Form "Ajukan kemitraan" di landing page
Route::post('/partnership', function (Request $request) {
    // SEMENTARA: baru memvalidasi, belum menyimpan ke database.
    // Nanti diganti dengan PartnershipController@store.
    $request->validate([
        'cafe_name' => 'required|string|max:100',
        'owner'     => 'required|string|max:100',
        'phone'     => ['required', 'regex:/^(\+62|62|0)8[0-9\s\-]{8,15}$/'],
        'district'  => 'required|string|max:50',
    ], [
        'cafe_name.required' => 'Nama cafe wajib diisi.',
        'owner.required'     => 'Nama pemilik wajib diisi.',
        'phone.required'     => 'Nomor WhatsApp wajib diisi.',
        'phone.regex'        => 'Format nomor WhatsApp tidak valid.',
        'district.required'  => 'Kecamatan wajib dipilih.',
    ]);

    return redirect(url('/') . '#mitra')
        ->with('success', 'Terima kasih! Pengajuan kemitraanmu sudah kami terima.');
})->name('partnership.store');


// ===== 2. AUTENTIKASI =====

// Pilih peran sebelum login (Pengguna / Mitra / Admin)
Route::view('/pilih-peran', 'auth.role')->name('role.select');

// Login: GET menampilkan formulir, POST memprosesnya
// Kalau peran yang dipilih "admin", GET /login dialihkan ke halaman login admin.
Route::get('/login', function () {
    return request('role') === 'admin'
        ? redirect()->route('admin.login')
        : view('auth.login');
})->name('login');

Route::post('/login', function () {
    // SEMENTARA: belum mengecek email/password. Nanti diganti dengan proses login sungguhan.
    // Setelah masuk, pengguna langsung ke halaman Explore (daftar cafe).
    // (Admin tidak lewat sini: dia dialihkan ke /admin/login. Mitra: nanti ke dashboard mitra.)
    return redirect()->route('explore');
});

// Register: GET menampilkan formulir, POST memprosesnya (setelah daftar -> halaman login)
Route::view('/register', 'auth.register')->name('register');
Route::post('/register', function () {
    // SEMENTARA: belum menyimpan user ke database.
    return redirect()->route('login');
});

// Logout: harus POST (tombol "Keluar" di menu pengguna).
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');

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
Route::post('/checkout', function () {
    // SEMENTARA: belum memproses pembayaran. Nanti diganti proses pesanan sungguhan.
    return redirect()->route('orders');
})->name('checkout.pay');
Route::view('/pesanan',   'pesanan')->name('orders');        // Semua pesanan (riwayat lengkap)
Route::view('/profil',    'profil')->name('profile');        // Profil pengguna
Route::post('/profil', function () {
    // SEMENTARA: belum menyimpan perubahan profil. Nanti diganti proses update sungguhan.
    return redirect()->route('profile')->with('success', 'Profil berhasil disimpan.');
})->name('profile.update');


// ===== 4. HALAMAN ADMIN =====

// 4a. Login admin. SENGAJA di luar grup di bawah, karena nanti grup itu dikunci middleware 'auth'.
//     Kalau halaman login ikut terkunci, admin tidak bisa masuk sama sekali.
Route::view('/admin/login', 'admin.login')->name('admin.login');
Route::post('/admin/login', function () {
    // SEMENTARA: belum mengecek email/password. Nanti diganti login sungguhan (cek role = admin).
    return redirect()->route('admin.dashboard');
})->name('admin.login.submit');

// 4b. Panel admin. Semua URL diawali /admin dan semua nama diawali "admin."
//     Nanti, untuk mengunci halaman admin, cukup tambahkan ->middleware('auth') pada grup ini.
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/kelola', [DashboardController::class, 'kelola'])
        ->name('kelola');

    Route::get('/kelolabeans', [DashboardController::class, 'kelolabeans'])
        ->name('kelolabeans');

    Route::get('/review', [DashboardController::class, 'review'])
        ->name('review');

    Route::get('/laporan', [DashboardController::class, 'laporan'])
        ->name('laporan');

    Route::get('/pengaturan', [DashboardController::class, 'pengaturan'])
        ->name('pengaturan');

    Route::view('/moderation', 'admin.moderation')
        ->name('moderation');

    Route::view('/orders', 'admin.orders')
        ->name('orders');
});