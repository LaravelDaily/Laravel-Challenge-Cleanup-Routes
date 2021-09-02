<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminUsersController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', \App\Http\Controllers\Admin\AdminDashboardController::class)->middleware('isAdmin')->name('index');

Route::resource('books', AdminBookController::class);

Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');

Route::resource('users', AdminUsersController::class)->except(['show','create','store']);    
