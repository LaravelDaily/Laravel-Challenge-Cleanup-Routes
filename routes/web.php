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

Route::middleware(['auth'])->group(function () {
    //I have no idea what to do with this 5 routes below and what to do with naming
    Route::get('book/create', [\App\Http\Controllers\BookController::class, 'create'])->name('books.create');
    Route::post('book/store', [\App\Http\Controllers\BookController::class, 'store'])->name('books.store');
    Route::get('book/{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->name('books.report.create');
    Route::post('book/{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->name('books.report.store');
    Route::get('book/{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->name('books.show');

    Route::group(['prefix' => '/user'], function() {
        Route::resource('books', 'BookController', ['only' => ['index', 'edit', 'update', 'destroy']]);

        Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('user.orders.index');

        Route::group(['prefix' => '/settings'], function() {
            Route::get('/', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('user.settings');
            Route::po(st('/{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('user.settings.update');
            Route::post('/password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('user.password.update');
        });
    });
});

Route::middleware(['isAdmin'])->group(function () {
    Route::group(['prefix' => '/admin'], function() {
        Route::get('/', \App\Http\Controllers\Admin\AdminDashboardController::class)->name('admin.index');
        
        Route::resource('books', 'AdminBookController', ['except' => ['show']]);
        Route::put('/book/approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->name('admin.books.approve');

        Route::resource('users', 'AdminUsersController', ['only' => ['index', 'edit', 'update', 'destroy']]); 
    });
});

require __DIR__ . '/auth.php';
