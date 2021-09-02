<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AdminDashboardController,
    AdminUsersController,
    AdminBookController
};

Route::name('admin.')->group(function() {
    Route::get('/', AdminDashboardController::class)->name('index');

    // Users
    Route::resource('users', AdminUsersController::class)->only([
        'index', 'edit', 'update', 'destroy'
    ]);

    // Books
    Route::put('books/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::resource('books', AdminBookController::class)->except(['show']);
});
