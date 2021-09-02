<?php

use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminDashboardController;


Route::get('', HomeController::class)->name('home');

Route::middleware(Authenticate::class)->group(function () {
    Route::resource('books', BookController::class)->only(['create', 'store']);
    Route::resource('books.report', BookReportController::class)
        ->scoped(['book' => 'slug'])
        ->only(['create', 'store']);

    Route::prefix('user')->name('user.')->group(function () {
        Route::resource('books', BookController::class)
            ->except(['create', 'show', 'store'])
            ->name('index', 'books.list')
            ->scoped(['book' => 'slug']);

        Route::get('orders', OrderController::class)->name('orders.index');

        Route::prefix('settings')->group(function () {
            Route::resource('settings', UserSettingsController::class)
                ->only(['index', 'update'])
                ->names(['index' => 'settings']);

            Route::resource('password', UserChangePassword::class)->only(['update']);
        });
    });
});

Route::resource('books', BookController::class)->only(['show'])->scoped(['book' => 'slug']);

Route::middleware(IsAdmin::class)->prefix('admin')->group(function () {
    Route::prefix('books')->name('admin.')->group(function () {
        Route::resource('books', AdminBookController::class);
        Route::put('approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    });

    Route::name('admin.')->group(function () {
        Route::get('', AdminDashboardController::class)->name('index');
        Route::resource('users', AdminUsersController::class)->except(['create', 'store', 'show',]);
    });
});

require __DIR__ . '/auth.php';
