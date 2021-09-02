<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

require __DIR__ . '/auth.php';

Route::get('book/{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->name('books.show');
Route::middleware(['auth'])->group(function () {
    Route::prefix('book')->group(function () {
        Route::get('create', [\App\Http\Controllers\BookController::class, 'create'])->name('books.create');
        Route::post('store', [\App\Http\Controllers\BookController::class, 'store'])->name('books.store');
        Route::get('{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->name('books.report.create');
        Route::post('{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->name('books.report.store');
    });

    Route::prefix('user')->group(function () {
        Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('user.orders.index');

        Route::get('settings', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('user.settings');
        Route::post('settings/{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('user.settings.update');
        Route::post('settings/password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('user.password.update');

        Route::prefix('book')->group(function () {
            Route::get('/', [\App\Http\Controllers\BookController::class, 'index'])->name('user.books.list');
            Route::get('{book:slug}/edit', [\App\Http\Controllers\BookController::class, 'edit'])->name('user.books.edit');
            Route::put('{book:slug}', [\App\Http\Controllers\BookController::class, 'update'])->name('user.books.update');
            Route::delete('{book}', [\App\Http\Controllers\BookController::class, 'destroy'])->name('user.books.destroy');
        });
    });
});

require_once __DIR__ . '/admin.php';
