<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\UserSettingsController;

// User Routes


Route::resource('book', BookController::class)->only(['create', 'store'])->names([
    'create' => 'books.create',
    'store' => 'books.store',
]);

Route::group([
    'prefix' => 'book',
    'as' => 'books.',
], function() {
    Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
    Route::post('{book}/report', [BookReportController::class, 'store'])->name('report.store');
});

Route::group([
    'prefix' => 'user',
    'as' => 'user.'
], function() {
    Route::delete('books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::resource('books', BookController::class)->only(['index', 'edit', 'update'])->names([
        'index' => 'books.list',
        'edit' => 'books.edit',
        'update' => 'books.update',
    ])->scoped([
        'book' => 'slug'
    ]);

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

    Route::group([
        'prefix' => 'settings'
    ], function(){
        Route::get('/', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
    });
});

