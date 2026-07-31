<?php

use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckOutController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ScanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API auth
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

/*
|--------------------------------------------------------------------------
| Authenticated API
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::put('/password', [AuthController::class, 'changePassword']);
    });

    Route::get('/summary', [DashboardController::class, 'summary']);
    Route::get('/scan/lookup', [ScanController::class, 'lookup']);

    Route::apiResource('categories', CategoryController::class);

    Route::get('/assets', [AssetController::class, 'index']);
    Route::post('/assets', [AssetController::class, 'store']);
    Route::get('/assets/{asset}', [AssetController::class, 'show']);
    Route::put('/assets/{asset}', [AssetController::class, 'update']);
    Route::patch('/assets/{asset}', [AssetController::class, 'update']);
    // Multipart-friendly update (PHP only parses uploaded files on POST)
    Route::post('/assets/{asset}/update', [AssetController::class, 'update']);
    Route::delete('/assets/{asset}', [AssetController::class, 'destroy']);
    Route::patch('/assets/{asset}/restore', [AssetController::class, 'restore']);
    Route::post('/assets/{asset}/archive', [AssetController::class, 'archive']);
    Route::post('/assets/{asset}/discard', [AssetController::class, 'discard']);

    Route::get('/checkouts', [CheckOutController::class, 'index']);
    Route::post('/checkouts', [CheckOutController::class, 'store']);
    Route::get('/checkouts/{checkout}', [CheckOutController::class, 'show']);
    Route::post('/checkouts/{checkout}/return', [CheckOutController::class, 'returnAsset']);

    Route::get('/activity', [ActivityLogController::class, 'index']);
    Route::post('/activity', [ActivityLogController::class, 'store']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllRead']);
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markRead']);
});
