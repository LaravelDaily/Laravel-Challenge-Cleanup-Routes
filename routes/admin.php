<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AdminDashboardController,
    AdminBookController,
    AdminUsersController,
};


/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web, isAdmin" middleware group. Now create something great!
|
*/


Route::get('/', AdminDashboardController::class)->name('index');
Route::resource('books', AdminBookController::class)->except('show');
Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
Route::resource('users', AdminUsersController::class)->only(['index', 'edit', 'update', 'destroy']);