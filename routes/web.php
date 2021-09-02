<?php

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
    Route::resource('book', \App\Http\Controllers\BookController::class)->only(['create', 'store'])->names('books');
    Route::resource('book.report', \App\Http\Controllers\BookReportController::class)->only(['create', 'store'])->scoped(['book' => 'slug'])->names('books.report');
    Route::get('book/{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->withoutMiddleware('auth')->name('books.show');

    Route::prefix('user')->name('user.')->group(function () {
        Route::resource('books', \App\Http\Controllers\BookController::class)->scoped(['book' => 'slug'])->names(['index' => 'books.list']);

        Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');

        Route::get('settings', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('settings');
        Route::post('settings/{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('password.update');
    });
});

require __DIR__ . '/auth.php';
