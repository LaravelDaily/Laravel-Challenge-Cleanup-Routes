<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\UserSettingsController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('book', BookController::class)->only('create', 'store', 'show')->scoped(['book' => 'slug']);
    Route::resource('book.report', \App\Http\Controllers\BookReportController::class)->only(['create', 'store'])->scoped(['book' => 'slug']);
    Route::prefix('user')->name('user.')->group(function () {
        Route::resource('books', BookController::class)->except('create', 'store', 'show')->names(['index' => 'books.list']);
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::resource('settings', UserSettingsController::class)->only('index', 'update')->parameters(['settings' => 'user']);
        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('user.password.update');
    });
});
require __DIR__ . '/auth.php';
