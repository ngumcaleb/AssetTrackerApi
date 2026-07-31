<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ScanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mobile compatibility aliases (no /api prefix)
|--------------------------------------------------------------------------
|
| The Expo app historically calls /auth/* against the host root. These
| aliases keep that working while the canonical API lives under /api/*.
| Do NOT register GET /assets here — it would collide with the web assets
| index route. Use /api/assets?code= or /api/scan/lookup instead.
|
*/

Route::middleware('api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);
            Route::put('/profile', [AuthController::class, 'updateProfile']);
            Route::put('/password', [AuthController::class, 'changePassword']);
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/scan/lookup', [ScanController::class, 'lookup']);
    });
});
