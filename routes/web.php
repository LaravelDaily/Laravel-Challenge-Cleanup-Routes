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
Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::middleware(['auth'])->group(function () {
    Route::resource('books', BookController::class)->only('create', 'store');
    Route::resource('books.report', BookReportController::class)
        ->only('create', 'store')
        ->scoped(['book' => 'slug']);
    Route::prefix('user')->name('user.')->group(function () {
        Route::resource('books', BookController::class)
            ->scoped(['book' => 'slug'])
            ->names(['index' => 'books.list']);
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::prefix('settings')->group(function () {
            Route::get('/', [UserSettingsController::class, 'index'])->name('settings');
            Route::post('{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        });
        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])
            ->name('password.update');
    });
});

Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('index');
    Route::resource('books', AdminBookController::class)->except('show');
    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::resource('users', AdminUsersController::class)->except('create', 'store');
});

require __DIR__ . '/auth.php';