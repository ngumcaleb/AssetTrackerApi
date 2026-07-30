<?php

use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckOutController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\NotificationController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);

    Route::get('/summary', [DashboardController::class, 'summary']);

    Route::name('api.')->group(function () {
        Route::apiResource('categories', CategoryController::class);

        Route::apiResource('assets', AssetController::class);
        Route::patch('/assets/{asset}/restore', [AssetController::class, 'restore']);

        Route::apiResource('checkouts', CheckOutController::class)->only(['index', 'store', 'show']);
        Route::post('/checkouts/{checkout}/return', [CheckOutController::class, 'returnAsset']);

        Route::get('/activity', [ActivityLogController::class, 'index']);
        Route::post('/activity', [ActivityLogController::class, 'store']);
    });

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::put('/notifications/read-all', [NotificationController::class, 'markAllRead']);
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markRead']);
});
