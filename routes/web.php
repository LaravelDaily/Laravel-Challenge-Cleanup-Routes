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
    Route::group(['prefix' => '/book', 'as' => 'books.'], function() {
        Route::get('create', [\App\Http\Controllers\BookController::class, 'create'])->name('create');
        Route::post('store', [\App\Http\Controllers\BookController::class, 'store'])->name('store');
        Route::get('{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->name('report.create');
        Route::post('{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->name('report.store');
        Route::get('{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->name('show');
    });


    Route::group(['prefix' => '/user', 'as' => 'user.'], function() {
        Route::resource('books', 'BookController', ['only' => ['index', 'edit', 'update', 'destroy']]);

        Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');

        Route::group(['prefix' => '/settings'], function() {
            Route::get('/', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('settings');
            Route::po(st('/{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('settings.update');
            Route::post('/password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('password.update');
        });
    });
});

Route::middleware(['isAdmin'])->group(function () {
    Route::group(['prefix' => '/admin', 'as' => 'admin.'], function() {
        Route::get('/', \App\Http\Controllers\Admin\AdminDashboardController::class)->name('index');
        
        Route::resource('books', 'AdminBookController', ['except' => ['show']]);
        Route::put('/book/approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->name('books.approve');

        Route::resource('users', 'AdminUsersController', ['only' => ['index', 'edit', 'update', 'destroy']]); 
    });
});

require __DIR__ . '/auth.php';
