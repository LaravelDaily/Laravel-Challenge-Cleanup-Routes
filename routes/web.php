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

Route::middleware('auth')->group(function () {
    Route::resource('book', BookController::class)->only(['create', 'store'])->names('books');

    Route::get('book/{book:slug}/report/create', [BookReportController::class, 'create'])->name('books.report.create');
    Route::post('book/{book}/report', [BookReportController::class, 'store'])->name('books.report.store');

    Route::resource('user/books', BookController::class, ['as' => 'user'])->name('index', 'user.books.list');

    Route::get('user/orders', [OrderController::class, 'index'])->name('user.orders.index');

    Route::prefix('user/settings')->as('user.')->group(function () {
        Route::get('/', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
    });
});
Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::middleware('isAdmin')->group(function () {
    Route::get('admin', AdminDashboardController::class)->name('admin.index');

    Route::put('admin/book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('admin.books.approve');
    Route::resource('admin/books', AdminBookController::class)->names('admin.books');

    Route::resource('admin/users', AdminUsersController::class)->names('admin.users')->except(['create', 'store', 'show']);
});

require __DIR__ . '/auth.php';
