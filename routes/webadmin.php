<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', AdminDashboardController::class)->name('index');

Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
Route::resource('books',AdminBookController::class)->except('show');

Route::resource('users',AdminUsersController::class)->only('index', 'edit', 'update', 'destroy',);


