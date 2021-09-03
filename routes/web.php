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

Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
    Route::group(['prefix' => '/books', 'as' => 'books.', 'middleware' => 'auth'], function () {
        Route::get('', [\App\Http\Controllers\BookController::class, 'index'])->name('list');
        Route::delete('', [\App\Http\Controllers\BookController::class, 'destroy'])->name('destroy');
        Route::put('{book:slug}',[\App\Http\Controllers\BookController::class, 'update'])->name('update');
        Route::get('{book:slug}/edit', [\App\Http\Controllers\BookController::class, 'edit'])->name('edit');
    });
    
    Route::group(['middleware' => 'auth'], function () {
        Route::group(['prefix' => 'settings'], function () {
            Route::group(['as'=>'settings'], function () {
                Route::get('', [\App\Http\Controllers\UserSettingsController::class, 'index']);
                Route::post('{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('.update');
            });
            Route::post('password/change', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('password.update');
        });
        Route::resource('orders', \App\Http\Controllers\OrderController::class);
    });
    
});

Route::group(['prefix' => 'book', 'as' => 'books.'], function () {
    Route::middleware(['auth'])->group(function () {
        Route::resource('', \App\Http\Controllers\BookController::class)->only(['create', 'store']);
        Route::get('{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->name('report.create');
        Route::post('{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->name('report.store');
    });
    Route::get('{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->name('show');
    
});


Route::group(['prefix' => 'admin', 'middleware' => 'isAdmin', 'as' => 'admin.'], function () {
    Route::get('', \App\Http\Controllers\Admin\AdminDashboardController::class)->name("index");
    Route::resource('books', \App\Http\Controllers\Admin\AdminBookController::class);
    Route::put('book/approve', '\App\Http\Controllers\Admin\AdminBookController@approveBook')->name("books.approve");
    Route::resource('users', \App\Http\Controllers\Admin\AdminUsersController::class);
});

require __DIR__ . '/auth.php';
