<?php

use Illuminate\Support\Facades\Routeuse;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\UserSettingsController;
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

Route::get('/', HomeController::class)->name('home');

Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'book', 'as' => 'books.'], function () {
        Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
        Route::post('{book}/report', [BookReportController::class, 'store'])->name('report.store');
    });

    Route::resource('books', BookController::class)->only([
        'create', 'store'
    ]);

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::group(['prefix' => 'books', 'as' => 'books.'], function () {
            Route::get('/', [BookController::class, 'index'])->name('list');
            Route::get('{book:slug}/edit', [BookController::class, 'edit'])->name('edit');
            Route::put('{book:slug}', [BookController::class, 'update'])->name('update');
            Route::delete('{book}', [BookController::class, 'destroy'])->name('destroy');
        });

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('settings', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
    });
});

Route::group(['middleware' => ['isAdmin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', AdminDashboardController::class)->name('index');
    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::resource('books', AdminBookController::class)->except(['approveBook']);
    Route::resource('users', AdminUsersController::class);
});

require __DIR__ . '/auth.php';
