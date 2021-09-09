<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\
{
  Admin\AdminDashboardController,
  Admin\AdminBookController,
  Admin\AdminUsersController,
};

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('books',AdminBookController::class)->except('show'); 

Route::put('/book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');

Route::resource('users',AdminUsersController::class)->except('store','create','show'); 
    
Route::get('/', AdminDashboardController::class)->name('index');

