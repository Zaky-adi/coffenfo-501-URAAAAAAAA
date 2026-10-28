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

Route::post('/v1/auth/register', [RegisterController::class, '__invoke'])
    ->middleware('throttle:5,60');
Route::post('/v1/auth/verify-email', [AccountRecoveryController::class, 'verifyEmail'])
    ->middleware('throttle:10,60');
Route::post('/v1/auth/resend-verification', [AccountRecoveryController::class, 'resendVerification'])
    ->middleware(['auth:sanctum', 'throttle:10,60']);
Route::post('/v1/auth/forgot-password', [AccountRecoveryController::class, 'forgotPassword'])
    ->middleware('throttle:10,60');
Route::post('/v1/auth/reset-password', [AccountRecoveryController::class, 'resetPassword'])
    ->middleware('throttle:10,60');
