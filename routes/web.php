<?php

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\HomeController;
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

Route::get('/', HomeController::class)->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('books', BookController::class)
                ->only(['create', 'store']);

    Route::get('books/{book:slug}', [BookController::class, 'show'])
                ->name('books.show')
                ->withoutMiddleware('auth');

    Route::resource('books.report', BookReportController::class)
                ->only(['create', 'store'])
                ->scoped(['book' => 'slug']);

    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::resource('books', BookController::class)
                    ->except(['create', 'store', 'show'])
                    ->scoped(['book' => 'slug'])
                    ->name('index', 'books.list');

        Route::get('orders', [OrderController::class, 'index'])
                    ->name('orders.index');

        Route::resource('settings', UserSettingsController::class)
                    ->only(['index', 'update'])
                    ->parameter('settings', 'user')
                    ->name('index', 'settings');

        Route::put('settings/password/change/{user}', [UserChangePassword::class, 'update'])
                    ->name('password.update');
    });
});

Route::group(['middleware' => 'isAdmin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', AdminDashboardController::class)
                ->name('index');

    Route::resource('books', AdminBookController::class)
                ->except('show');

    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])
                ->name('books.approve');

    Route::resource('users', AdminUsersController::class)
                ->except(['create', 'store', 'show']);
});

require __DIR__ . '/auth.php';
