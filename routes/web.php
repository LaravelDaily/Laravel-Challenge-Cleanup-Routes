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

/**
 * PUBLIC ROUTES
 */

Route::get('/', HomeController::class)->name('home');
Route::get('book/{book}', [BookController::class, 'show'])->name('books.show');


/**
 * AUTH ROUTES
 */

Route::middleware('auth')->group(function () {

    Route::resource('books', BookController::class)->only(['create', 'store']);
    Route::resource('books.report', BookReportController::class)->only(['create', 'store']);

    Route::name('user.')->group(function () {

        Route::resource('orders', OrderController::class)->only('index');
        Route::post('password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');

        Route::resource('books', BookController::class,
            ['names' => ['index' => 'books.list']]
        )->except(['show', 'create', 'store']);

        Route::prefix('settings')->name('settings')->group(function () {
            Route::get('/', [UserSettingsController::class, 'index']);
            Route::post('/{user}', [UserSettingsController::class, 'update'])->name('.update');
        });

    });
});


/**
 * ADMIN ROUTES
 */

Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', AdminDashboardController::class)->name('index');

    Route::resource('books', AdminBookController::class);
    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');

    Route::resource('users', AdminUsersController::class)->only(['index', 'edit', 'update', 'destroy']);
});
