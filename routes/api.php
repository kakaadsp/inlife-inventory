<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BorrowingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ItemController;
use Illuminate\Support\Facades\Route;

// ─── API v1 ───────────────────────────────────────────────────────────────────
Route::prefix('v1')->name('api.v1.')->group(function () {

    // Public: Authentication
    Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('auth/me',     [AuthController::class, 'me'])->name('auth.me');

        // Dashboard stats
        Route::get('dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

        // Categories
        Route::apiResource('categories', CategoryController::class);

        // Items
        Route::apiResource('items', ItemController::class);

        // Borrowings
        Route::apiResource('borrowings', BorrowingController::class)->only(['index', 'show', 'store']);
        Route::post('borrowings/{borrowing}/return', [BorrowingController::class, 'processReturn'])
            ->name('borrowings.return');
    });
});
