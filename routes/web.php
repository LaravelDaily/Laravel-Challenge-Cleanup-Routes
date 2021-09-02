<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminBookController;
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

// auth middleware routes
Route::middleware('auth')->group(function(){
    Route::resource('books', BookController::class)->only(['create', 'store']);

    Route::resource('books.report', BookReportController::class)->only(['create', 'store'])->scoped(['book' => 'slug']);

    Route::prefix('user/')->group(function(){
        Route::name('user.')->group(function(){
            Route::resource('books', BookController::class)->except(['create', 'show', 'store'])->name('index', 'books.list')->scoped(['book' => 'slug']);

            Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

            Route::resource('settings', UserSettingsController::class)->only(['index'])->names(['index' => 'settings']);

            Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update'); // I don't know why throws a 405 error (method not allowed) if is in route::resource

            Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
        });
    });
});

Route::resource('books', BookController::class)->only(['show'])->scoped(['book' => 'slug']);

// isAdmin middleware routes
Route::middleware('isAdmin')->group(function(){
    Route::prefix('admin/')->group(function(){
        Route::name('admin.')->group(function(){
            Route::get('', AdminDashboardController::class)->name('index');

            Route::resource('users', AdminUsersController::class)->except(['create', 'show']);

            Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
            Route::resource('books', AdminBookController::class)->except(['show']);
        });
    });
});

require __DIR__ . '/auth.php';
