<?php

use App\Http\Controllers\Web\ActivityLogController;
use App\Http\Controllers\Web\AssetController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\CheckOutController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\ScanController;
use App\Http\Controllers\Web\SearchController;
use App\Http\Controllers\Web\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:web');

Route::get('/registration-success', function () {
    return view('auth.registration-success');
})->name('registration-success')->middleware('auth:web');

Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
    Route::get('/assets/create', [AssetController::class, 'create'])->name('assets.create');
    Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
    Route::get('/assets/{asset}', [AssetController::class, 'show'])->name('assets.show');
    Route::get('/assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
    Route::put('/assets/{asset}', [AssetController::class, 'update'])->name('assets.update');
    Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');
    Route::get('/assets-archived', [AssetController::class, 'archived'])->name('assets.archived');
    Route::post('/assets/{asset}/restore', [AssetController::class, 'restore'])->name('assets.restore');
    Route::get('/assets/{asset}/print-qr', [AssetController::class, 'printQr'])->name('assets.print-qr');
    Route::get('/assets/{asset}/history', [AssetController::class, 'history'])->name('assets.history');
    Route::post('/assets/{asset}/archive', [AssetController::class, 'archive'])->name('assets.archive');
    Route::post('/assets/{asset}/discard', [AssetController::class, 'discard'])->name('assets.discard');

    Route::get('/checkouts', [CheckOutController::class, 'index'])->name('checkouts.index');
    Route::get('/checkouts/create', [CheckOutController::class, 'create'])->name('checkouts.create');
    Route::post('/checkouts', [CheckOutController::class, 'store'])->name('checkouts.store');
    Route::get('/checkouts/{checkout}', [CheckOutController::class, 'show'])->name('checkouts.show');
    Route::get('/checkouts/{checkout}/return', [CheckOutController::class, 'returnForm'])->name('checkouts.return');
    Route::post('/checkouts/{checkout}/return', [CheckOutController::class, 'returnAsset'])->name('checkouts.process-return');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/activity', [ActivityLogController::class, 'index'])->name('activity.index');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
    Route::post('/scan/lookup', [ScanController::class, 'lookup'])->name('scan.lookup');
});
