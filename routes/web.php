<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MonlamMelongFinetuningController;
use App\Http\Controllers\MonlamBenchmarkController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // User Management (Admin Only)
    Route::prefix('admin')->name('admin.')->middleware(['auth', '\App\Http\Middleware\AdminMiddleware'])->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    });

    // Routes for MonlamMelongFinetuning entries
    Route::middleware(['\App\Http\Middleware\CategoryAccessMiddleware'])->group(function () {
        Route::get('/entries', [MonlamMelongFinetuningController::class, 'index'])->name('entries.index');
        Route::get('/entries/create', [MonlamMelongFinetuningController::class, 'create'])->name('entries.create');
        Route::get('/entries/{entry}', [MonlamMelongFinetuningController::class, 'show'])->name('entries.show');
        Route::post('/entries', [MonlamMelongFinetuningController::class, 'store'])->name('entries.store');
        Route::get('/entries/{entry}/edit', [MonlamMelongFinetuningController::class, 'edit'])->name('entries.edit');
        Route::put('/entries/{entry}', [MonlamMelongFinetuningController::class, 'update'])->name('entries.update');
        Route::delete('/entries/{entry}', [MonlamMelongFinetuningController::class, 'destroy'])->name('entries.destroy');
        Route::put('/entries/{entry}/status', [MonlamMelongFinetuningController::class, 'updateStatus'])->name('entries.update-status');
    });

    Route::get('/entries/export', [MonlamMelongFinetuningController::class, 'export'])->name('entries.export');
    Route::get('/entries/download', [MonlamMelongFinetuningController::class, 'download'])->name('entries.download');

    // Admin Routes - Category Management
    Route::middleware(['auth', '\App\Http\Middleware\AdminMiddleware'])->group(function () {
        Route::get('/admin/categories', [MonlamMelongFinetuningController::class, 'categoryIndex'])->name('admin.categories.index');
        Route::post('/admin/categories', [MonlamMelongFinetuningController::class, 'categoryStore'])->name('admin.categories.store');
        Route::put('/admin/categories/{category}', [MonlamMelongFinetuningController::class, 'categoryUpdate'])->name('admin.categories.update');
        Route::delete('/admin/categories/{category}', [MonlamMelongFinetuningController::class, 'categoryDestroy'])->name('admin.categories.destroy');

        // Admin Routes - Tag Management
        Route::get('/admin/tags', [MonlamMelongFinetuningController::class, 'tagIndex'])->name('admin.tags.index');
        Route::post('/admin/tags', [MonlamMelongFinetuningController::class, 'tagStore'])->name('admin.tags.store');
        Route::put('/admin/tags/{tag}', [MonlamMelongFinetuningController::class, 'tagUpdate'])->name('admin.tags.update');
        Route::delete('/admin/tags/{tag}', [MonlamMelongFinetuningController::class, 'tagDestroy'])->name('admin.tags.destroy');
    });

    // Additional routes for entry workflow
    Route::match(['post', 'put', 'get'], '/entries/{entry}/submit-for-review', [MonlamMelongFinetuningController::class, 'submitForReview'])->name('entries.submit-for-review');
    Route::get('/entries/{entry}/submit-via-get', [MonlamMelongFinetuningController::class, 'submitViaGet'])->name('entries.submit-via-get');
    Route::post('/entries/{entry}/review', [MonlamMelongFinetuningController::class, 'review'])->name('entries.review');
    Route::get('/review-queue', [MonlamMelongFinetuningController::class, 'reviewQueue'])->name('entries.review-queue');

    // Benchmark routes - index available to all authenticated users
    Route::get('/benchmark', [MonlamBenchmarkController::class, 'index'])->name('benchmark.index');

    // Benchmark management routes - restricted by custom benchmark permissions middleware
    Route::middleware('\App\Http\Middleware\BenchmarkManagementMiddleware')->group(function () {
        Route::get('/benchmark/create', [MonlamBenchmarkController::class, 'create'])->name('benchmark.create');
        Route::post('/benchmark', [MonlamBenchmarkController::class, 'store'])->name('benchmark.store');
        Route::get('/benchmark/{monlamBenchmark}/edit', [MonlamBenchmarkController::class, 'edit'])->name('benchmark.edit');
        Route::put('/benchmark/{monlamBenchmark}', [MonlamBenchmarkController::class, 'update'])->name('benchmark.update');
        Route::delete('/benchmark/{monlamBenchmark}', [MonlamBenchmarkController::class, 'destroy'])->name('benchmark.destroy');
    });

    // Show benchmark route must come after specific routes
    Route::get('/benchmark/{monlamBenchmark}', [MonlamBenchmarkController::class, 'show'])->name('benchmark.show');
});

require __DIR__.'/auth.php';
