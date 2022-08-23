<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{AdminBookController, AdminDashboardController, AdminUsersController};

Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('index');
    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::resources([
        'books' => AdminBookController::class,
        'users' => AdminUsersController::class
    ]);
});
