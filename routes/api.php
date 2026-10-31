<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AccountRecoveryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\ProfileController;

Route::post('/v1/auth/register', [RegisterController::class, '__invoke'])
    ->middleware('throttle:5,60');
Route::post('/v1/auth/login', LoginController::class)
    ->middleware('throttle:60,1');
Route::post('/v1/auth/refresh', [SessionController::class, 'refresh'])
    ->middleware('throttle:30,1');
Route::post('/v1/auth/logout', [SessionController::class, 'logout'])
    ->middleware(['auth:sanctum', 'throttle:30,1']);
Route::post('/v1/auth/logout-all', [SessionController::class, 'logoutAll'])
    ->middleware(['auth:sanctum', 'throttle:30,1']);
Route::post('/v1/auth/verify-email', [AccountRecoveryController::class, 'verifyEmail'])
    ->middleware('throttle:10,60');
Route::post('/v1/auth/resend-verification', [AccountRecoveryController::class, 'resendVerification'])
    ->middleware(['auth:sanctum', 'throttle:10,60']);
Route::post('/v1/auth/forgot-password', [AccountRecoveryController::class, 'forgotPassword'])
    ->middleware('throttle:10,60');
Route::post('/v1/auth/reset-password', [AccountRecoveryController::class, 'resetPassword'])
    ->middleware('throttle:10,60');

Route::middleware(['auth:sanctum', 'throttle:300,1'])->group(function (): void {
    Route::get('/v1/me', [ProfileController::class, 'show']);
    Route::patch('/v1/me', [ProfileController::class, 'update']);
    Route::patch('/v1/me/password', [ProfileController::class, 'updatePassword']);
    Route::put('/v1/me/preferences', [ProfileController::class, 'updatePreferences']);
    Route::delete('/v1/me', [ProfileController::class, 'destroy']);
});
