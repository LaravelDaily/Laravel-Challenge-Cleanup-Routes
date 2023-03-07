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

Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

Route::group(['middleware' => ['auth'], 'prefix' => 'book', 'as' => 'books.'], function () {
    Route::get('create', [BookController::class, 'create'])->name('create');
    Route::post('store', [BookController::class, 'store'])->name('store');
    Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
    Route::post('{book}/report', [BookReportController::class, 'store'])->name('report.store');
    Route::get('{book:slug}', [BookController::class, 'show'])->withoutMiddleware(['auth'])->name('show');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('books', [BookController::class, 'index'])->name('books.list');
    Route::get('books/{book:slug}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('books/{book:slug}', [BookController::class, 'update'])->name('books.update');
    Route::delete('books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::get('user/orders', [OrderController::class, 'index'])->name('orders.index');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('settings', [UserSettingsController::class, 'index'])->name('settings');
    Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
});

Route::group(['middleware' => ['isAdmin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('admin', AdminDashboardController::class)->name('admin.index');

    Route::get('books', [AdminBookController::class, 'index'])->name('books.index');
    Route::get('books/create', [AdminBookController::class, 'create'])->name('books.create');
    Route::post('books', [AdminBookController::class, 'store'])->name('books.store');
    Route::get('books/{book}/edit', [AdminBookController::class, 'edit'])->name('books.edit');
    Route::put('books/{book}', [AdminBookController::class, 'update'])->name('books.update');
    Route::delete('books/{book}', [AdminBookController::class, 'destroy'])->name('books.destroy');
    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
});

Route::group(['middleware' => ['isAdmin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('users', [AdminUsersController::class, 'index'])->name('users.index');
    Route::get('users/{user}/edit', [AdminUsersController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [AdminUsersController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [AdminUsersController::class, 'destroy'])->name('users.destroy');
});

require __DIR__ . '/auth.php';
