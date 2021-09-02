<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    BookController,
    BookReportController,
    OrderController,
    UserChangePassword,
    UserSettingsController
};

/*
 * Routes accessible by authenticated users.
 *
 * middleware: 'web', 'auth'
 */

Route::prefix("book")->name("books.")->group(function (){
    Route::get('create', [BookController::class, 'create'])->name('create');
    Route::post('store', [BookController::class, 'store'])->name('store');
    Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
    Route::post('{book}/report', [BookReportController::class, 'store'])->name('report.store');
});

Route::prefix("user")->name("user.")->group(function(){
    Route::get('books', [BookController::class, 'index'])->name('books.list');
    Route::get('books/{book:slug}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('books/{book:slug}', [BookController::class, 'update'])->name('books.update');
    Route::delete('books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

    Route::get('settings', [UserSettingsController::class, 'index'])->name('settings');
    Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
});
