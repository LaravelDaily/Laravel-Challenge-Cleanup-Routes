<?php

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\UserSettingsController;
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

Route::view('/', 'front.home')->name('home');

Route::get('books/{books:slug}', [BookController::class, 'show'])->name('books.show');

Route::group(['middleware' => 'auth'], function () {

    Route::resource('books', BookController::class)->except('show');
    Route::resource('books.report', BookReportController::class )->scoped(['book' => 'slug']);

    Route::group(['prefix' => 'user' , 'as' => 'user.'], function () {

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

        Route::resource('books', BookController::class)->scoped(['book' => 'slug'])
        ->names(['index' => 'books.list',])
        ->only('index', 'edit', 'update', 'destroy');

        Route::resource('settings', UserSettingsController::class)->names(['index' => 'settings'])
        ->only('index', 'update');
        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
    });

    Route::group(['prefix' => 'admin', 'middleware' => 'isAdmin' , 'as' => 'admin.'], function () {

        Route::get('/', AdminDashboardController::class)->name('index');
        
        Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
        Route::resource('books', AdminBookController::class);

        Route::resource('users', AdminUsersController::class)->except('show');

    });
});


require __DIR__ . '/auth.php';
