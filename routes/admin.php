<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AdminDashboardController, AdminBookController, AdminUsersController
};

Route::get('/', AdminDashboardController::class)->name('index');

Route::resource('books', AdminBookController::class);
Route::put('admin/book/approve/{book}', [AdminBookController::class, 'approveBook'])->middleware('isAdmin')->name('books.approve');

Route::resource('users', AdminUsersController::class)->only(['index','edit','update','destroy']);
