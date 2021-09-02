<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
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

Route::middleware('auth')->group(function () {
    Route::resource('books', BookController::class)
        ->only('create', 'store')
        ->scoped(['book' => 'slug']);

    Route::resource('books.report', BookReportController::class)
        ->only('create', 'store')
        ->scoped(['book' => 'slug']);

    Route::prefix('user')->name('user.')->group(function () {
        Route::resource('books', BookController::class)
            ->except('create', 'store')
            ->scoped(['book' => 'slug'])
            ->name('index', 'books.list');

        Route::get('settings', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    });
});

Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('index');
    Route::resource('books', AdminBookController::class)->except('show');
    Route::resource('users', AdminUsersController::class)->except('create', 'store', 'show');
    Route::put('admin/book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
});

Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

require __DIR__ . '/auth.php';