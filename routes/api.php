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
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\CafeCatalogController;
use App\Http\Controllers\PartnershipController;
use App\Http\Controllers\CafeManagementController;

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

foreach (['areas', 'beans', 'species', 'roast-levels', 'brew-methods', 'facilities', 'menu-categories'] as $masterType) {
    Route::get('/v1/master/' . $masterType, [MasterDataController::class, 'index'])
        ->defaults('type', $masterType)
        ->middleware('throttle:120,1');
}

Route::middleware(['auth:sanctum', 'admin', 'throttle:300,1'])->prefix('/v1/admin/master')->group(function (): void {
    Route::post('/{type}', [MasterDataController::class, 'store']);
    Route::patch('/{type}/{id}', [MasterDataController::class, 'update'])->whereNumber('id');
});

Route::get('/v1/cafes/nearby', [CafeCatalogController::class, 'nearby'])->middleware('throttle:120,1');
Route::get('/v1/cafes/popular', [CafeCatalogController::class, 'popular'])->middleware('throttle:120,1');
Route::get('/v1/cafes/featured', [CafeCatalogController::class, 'featured'])->middleware('throttle:120,1');
Route::get('/v1/testimonials', [CafeCatalogController::class, 'testimonials'])->middleware('throttle:120,1');
Route::get('/v1/cafes', [CafeCatalogController::class, 'index'])->middleware('throttle:120,1');
Route::get('/v1/cafes/{id}/similar', [CafeCatalogController::class, 'similar'])->whereUuid('id');
Route::get('/v1/cafes/{id}', [CafeCatalogController::class, 'show'])->whereUuid('id');
Route::post('/v1/partnership-requests', [PartnershipController::class, 'store'])->middleware('throttle:3,60');

Route::middleware(['auth:sanctum', 'cafe_owner', 'throttle:300,1'])->prefix('/v1/owner/cafes')->group(function (): void {
    Route::get('/', [CafeManagementController::class, 'ownerIndex']);
    Route::post('/', [CafeManagementController::class, 'ownerCreate']);
    Route::patch('/{publicId}', [CafeManagementController::class, 'ownerUpdate'])->whereUuid('publicId');
    Route::put('/{publicId}/accepts-orders', [CafeManagementController::class, 'setAcceptsOrders'])->whereUuid('publicId');
});
Route::middleware(['auth:sanctum', 'admin', 'throttle:300,1'])->prefix('/v1/admin')->group(function (): void {
    Route::get('/cafes', [CafeManagementController::class, 'adminIndex']);
    Route::get('/cafes/{publicId}', [CafeManagementController::class, 'adminShow'])->whereUuid('publicId');
    Route::post('/cafes', [CafeManagementController::class, 'adminCreate']);
    Route::patch('/cafes/{publicId}', [CafeManagementController::class, 'adminEdit'])->whereUuid('publicId');
    Route::put('/featured-cafes/{id}', [CafeManagementController::class, 'setFeatured'])->whereNumber('id');
    Route::delete('/featured-cafes/{id}', [CafeManagementController::class, 'deleteFeatured'])->whereNumber('id');
    Route::get('/partnership-requests', [PartnershipController::class, 'index']);
    Route::patch('/partnership-requests/{publicId}', [PartnershipController::class, 'update'])->whereUuid('publicId');
});
