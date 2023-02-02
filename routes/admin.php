<?php
use App\Http\Controllers\Admin\{
    AdminBookController,
    AdminDashboardController,
    AdminUsersController
};

Route::get('/', AdminDashboardController::class)->name('index');

Route::resource('books', AdminBookController::class)->except('show');
Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');

Route::resource('users', AdminUsersController::class)
    ->only(['index', 'edit', 'update', 'destroy']);