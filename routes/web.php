<?php

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\UserSettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('/books', BookController::class)->only(['create', 'store']);

    Route::name('books.')->prefix('book')->group(function () {
        Route::get('/{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
        Route::post('/{book}/report', [BookReportController::class, 'store'])->name('report.store');
        Route::get('book/{book:slug}', [BookController::class, 'show'])->name('show')->withoutMiddleware('auth');
    });

    Route::name('user.')->prefix('user')->group(function () {
        Route::resource('/books', BookController::class)->only(['index', 'edit', 'update'])
            ->names(['index' => 'books.list'])
            ->scoped(['book' => 'slug']);

        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

        Route::get('/settings', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('/settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
    });
});

Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('index');

    Route::resource('/books', AdminBookController::class)->except('show');
    Route::put('/book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');

    Route::resource('/users', AdminUsersController::class)->except(['create', 'store', 'show']);
});

require __DIR__ . '/auth.php';
