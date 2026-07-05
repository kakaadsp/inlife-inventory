<?php

declare(strict_types=1);

use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ─── Guest Routes ─────────────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

// ─── Authenticated Routes ──────────────────────────────────────────────────────
Route::middleware(['auth', 'active'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Admin & Staff: Full Manage ──────────────────────────────────────────
    Route::middleware('role:admin,staff')->group(function () {

        // Categories
        Route::resource('categories', CategoryController::class)->except(['show']);

        // Items
        Route::resource('items', ItemController::class);

        // Borrowings
        Route::get('borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
        Route::post('borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
        Route::post('borrowings/{borrowing}/return', [BorrowingController::class, 'processReturn'])->name('borrowings.return');
    });

    // ── Admin, Staff & Manager: Read + Export ──────────────────────────────
    Route::middleware('role:admin,staff,manager')->group(function () {

        // Borrowings - read
        Route::get('borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
        Route::get('borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.show');
        Route::get('borrowings/history/all', [BorrowingController::class, 'history'])->name('borrowings.history');

        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export-items', [ReportController::class, 'exportItems'])->name('reports.export-items');
        Route::get('reports/export-borrowings', [ReportController::class, 'exportBorrowings'])->name('reports.export-borrowings');
        Route::get('reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    });

    // ── Admin Only: User Management ────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    // ── Profile (All authenticated users) ────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ─── Setup Route (TEMPORARY - DELETE AFTER USE) ────────────────────────────
Route::get('/setup-magang', function () {
    try {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\Item::truncate();
        \App\Models\Category::truncate();
        \App\Models\User::truncate();
        \App\Models\Role::truncate();
        \App\Models\Setting::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('storage:link', ['--force' => true]);
        return response('BOOM! Setup Database dan Storage Berhasil 100%! '
            . '<br><br>Silakan login dengan:<br>'
            . 'Admin: admin@inlife.co.id / password<br>'
            . 'Staff: staff@inlife.co.id / password<br>'
            . 'Manager: manager@inlife.co.id / password', 200);
    } catch (\Exception $e) {
        return response('ERROR: ' . $e->getMessage(), 500);
    }
});
