<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\UserSettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::prefix('settings')->group(function () {
        Route::get('/', [UserSettingsController::class, 'index'])->name('user.settings');
        Route::post('{user}', [UserSettingsController::class, 'update'])->name('user.settings.update');
        Route::post('password/change/{user}', [UserChangePassword::class, 'update'])->name('user.password.update');
    });

    Route::resource('book', BookController::class)->names([
        'index' => 'user.books.list',
        'edit' => 'user.books.edit',
        'update' => 'user.books.update',
        'destroy' => 'user.books.destroy'
    ])->parameter('book', 'book:slug');

    Route::get('orders', [OrderController::class, 'index'])->name('user.orders.index');
});

require __DIR__ . '/auth.php';
