<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    BookController,
    BookReportController,
    OrderController,
    UserSettingsController,
    UserChangePassword,
};

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

Route::middleware(['auth'])->group(function () {
    Route::resource('book', BookController::class)->only(['create', 'store'])->names([
        'create' => 'books.create',
        'store' => 'books.store',
    ]);
    Route::prefix('book')->name('books.')->group(function () {
        Route::get('{book:slug}', [BookController::class, 'show'])->name('show')->withoutMiddleware('auth');
        Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
        Route::post('{book}/report', [BookReportController::class, 'store'])->name('report.store');
    });
    Route::prefix('user')->name('user.')->group(function () {
        Route::resource('books', BookController::class)->only(['index', 'edit', 'update', 'destroy'])->name('index', 'books.list')->parameter('books', 'book:slug');
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::prefix('settings')->group(function () {
            Route::get('/', [UserSettingsController::class, 'index'])->name('settings');
            Route::post('{user}', [UserSettingsController::class, 'update'])->name('settings.update');
            Route::post('password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
        });
    });
});

require __DIR__ . '/auth.php';