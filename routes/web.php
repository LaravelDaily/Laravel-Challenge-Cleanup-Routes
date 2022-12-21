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
Route::group(['middleware' => 'auth'], function () {

    Route::group(['prefix' => 'book', 'as' => 'books.'], function () {
        Route::get('{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->name('report.create');
        Route::post('{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->name('report.store');
        Route::get('create', [\App\Http\Controllers\BookController::class, 'create'])->name('create');
        Route::post('store', [\App\Http\Controllers\BookController::class, 'store'])->name('store');
    });

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::delete('books/{book}', [\App\Http\Controllers\BookController::class, 'destroy'])->name('books.destroy');
        Route::resource('books', \App\Http\Controllers\BookController::class)->only('index', 'edit', 'update')
            ->names(['index' => 'books.list', 'edit' => 'books.edit', 'update' => 'books.update'])
            ->scoped(['book' => 'slug']);
        Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
        Route::get('settings', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('settings');
        Route::post('settings/{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('password.update');
    });
});
Route::get('book/{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->name('books.show');

Route::group(['middleware' => 'isAdmin', 'as' => 'admin.', 'prefix' => 'admin'], function () {
    Route::get('admin', \App\Http\Controllers\Admin\AdminDashboardController::class)->name('index');
    Route::put('book/approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::resource('books', \App\Http\Controllers\Admin\AdminBookController::class)->except('show');
    Route::resource('users', \App\Http\Controllers\Admin\AdminUsersController::class)->only('index', 'edit', 'update', 'destroy');
});
require __DIR__ . '/auth.php';
