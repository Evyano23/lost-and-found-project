<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Public item discovery routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('items.index');
    }

    return view('landing');
})->name('home');
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/map', [ItemController::class, 'map'])->name('items.map');

// notification routes
Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

// Authenticated item & claim management
Route::middleware('auth')->group(function () {
    // Reporting & Managing Items
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/my-items', [ItemController::class, 'myItems'])->name('items.my-items');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    // Claims Management
    Route::post('/items/{item}/claims', [ClaimController::class, 'store'])->name('claims.store');
    Route::get('/items/{item}/claims', [ClaimController::class, 'itemClaims'])->name('claims.item-claims');
    Route::get('/my-claims', [ClaimController::class, 'myClaims'])->name('claims.my-claims');
    Route::post('/claims/{claim}/approve', [ClaimController::class, 'approve'])->name('claims.approve');
    Route::post('/claims/{claim}/reject', [ClaimController::class, 'reject'])->name('claims.reject');
    Route::post('/claims/{claim}/cancel', [ClaimController::class, 'cancel'])->name('claims.cancel');

    // Reporting routes (authenticated users)
    Route::middleware('auth')->group(function () {
        Route::post('/report/{type}/{id}', [ReportController::class, 'store'])
            ->where('type', 'item|claim')
            ->name('report.store');

        Route::get('/my-reports', [ReportController::class, 'myReports'])
            ->name('report.mine');
    });

    // Admin Panel Group
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::post('/users/{user}/toggle-admin', [AdminDashboardController::class, 'toggleAdmin'])->name('users.toggleAdmin');
        Route::delete('/users/{user}', [AdminDashboardController::class, 'deleteUser'])->name('users.delete');

        // Item Moderation
        Route::get('/items', [AdminDashboardController::class, 'items'])->name('items');
        Route::delete('/items/{item}', [AdminDashboardController::class, 'deleteItem'])->name('items.delete');

        // Claims Oversight
        Route::get('/claims', [AdminDashboardController::class, 'claims'])->name('claims');

        // Reports Queue
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::patch('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    });
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Item detail (must follow /items/create to avoid route shadowing)
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

Route::get('/dashboard', function () {
    return redirect()->route('items.index');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
