<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AdminDashboardController,
    AdminBookController,
    AdminUsersController
};

Route::get('', AdminDashboardController::class)->name('index');
Route::resource('books', AdminBookController::class)->except('show');

Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
Route::resource('users', AdminUsersController::class)->except(['create', 'store', 'show']);