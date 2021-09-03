<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminUsersController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/', AdminDashboardController::class)->name('index');
Route::resource('books', AdminBookController::class)->except('show');
Route::put('books/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
Route::resource('users', AdminUsersController::class)->except([ 'create', 'store', 'show']);
