<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Admin\AdminBookController,
    Admin\AdminDashboardController,
    Admin\AdminUsersController,
    BookController,
    BookReportController,
    OrderController,
    UserSettingsController,
    UserChangePassword
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

Route::group(['middleware' => 'auth', 'prefix' => 'book'], function () {
    Route::resource('/', BookController::class)->only('create', 'store');

    Route::group(['as' => 'books.'], function () {
        Route::get('create', [BookController::class, 'create'])->name('create');
        Route::post('store', [BookController::class, 'store'])->name('store');

        Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
        Route::post('{book}/report', [BookReportController::class, 'store'])->name('report.store');
    });
});

Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::group(['middleware' => 'auth', 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::resource('books', BookController::class)->only(['index', 'edit', 'update', 'destroy'])->name('index', 'books.list')->parameter('books', 'book:slug');
});

Route::group(['middleware' => 'auth', 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->middleware('auth')->name('orders.index');

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', [\App\Http\Controllers\UserSettingsController::class, 'index'])->middleware('auth')->name('settings');
        Route::post('{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->middleware('auth')->name('settings.update');
        Route::post('password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->middleware('auth')->name('password.update');
    });
});

Route::group(['middleware' => 'isAdmin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', AdminDashboardController::class)->name('index');
    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::resources([
        'books' => AdminBookController::class,
        'users' => AdminUsersController::class,
    ]);
});

require __DIR__ . '/auth.php';
