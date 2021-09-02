<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\UserChangePassword;

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

Route::get('/', HomeController::class)->name('home');

Route::middleware(['auth'])->group(function () {

    Route::get('books', [BookController::class, 'index'])->name('user.books.list');
    
    Route::resource('books', BookController::class)->only('create', 'store');

    Route::prefix('books')->name('books.')->group(function () {
        Route::resource('{book}/report', BookReportController::class)->only(['create', 'store']);
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::resource('books', BookReportController::class)->only(['create', 'store']);

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

        Route::get('settings', [UserSettingsController::class, 'index'])->name('settings');

        Route::resource('settings', UserSettingsController::class)->only(['update']);

        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
    });

});

Route::get('books/{book:slug}', [BookController::class, 'show'])->name('books.show');