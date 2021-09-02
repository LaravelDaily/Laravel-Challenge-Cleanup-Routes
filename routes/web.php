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

Route::get('/')->uses(HomeController::class)->name('home');

Route::delete('user/books/{book:id}')->uses([BookController::class, 'destroy'])->name('user.books.destroy');
Route::resource('books', BookController::class)->only(['create', 'store', 'show']);

Route::middleware('auth')->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::resource('books', BookController::class)->only(['index', 'edit', 'update'])->names(['index' => 'books.list']);
        Route::resource('orders', OrderController::class)->only(['index']);

        Route::get('settings')->uses([UserSettingsController::class, 'index'])->name('settings');
        Route::post('settings/{user}')->uses([UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/password/change/{user}')->uses([UserChangePassword::class, 'update'])->name('password.update');
    });
    Route::get('book/{book}/report/create')->uses([BookReportController::class, 'create'])->name('books.report.create');
    Route::post('book/{book:id}/report')->uses([BookReportController::class, 'store'])->name('books.report.store');
});

require __DIR__ . '/auth.php';
