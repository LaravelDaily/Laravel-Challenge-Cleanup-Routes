<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Admin` Routes
|--------------------------------------------------------------------------
*/


Route::get('/', AdminDashboardController::class)->name('index');

Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
Route::resource('books', AdminBookController::class);

Route::resource('users', AdminUsersController::class)->except(['create', 'store']);
