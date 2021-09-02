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
    Route::resource('book', BookController::class)->only(['create', 'store'])->names(['create' => 'books.create', 'store' => 'books.store']);
    Route::get('book/{book:slug}/report/create', [BookReportController::class, 'create'])->name('books.report.create');
    Route::post('book/{book}/report', [BookReportController::class, 'store'])->name('books.report.store');

    Route::prefix('user')->as('user.')->group(function () {
        Route::resource('books', BookController::class)->only(['index', 'edit', 'update'])->scoped(['book' => 'slug'])->names(['index' => 'books.list']);
        Route::delete('books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

        Route::get('settings', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
    });
});

Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::middleware('isAdmin')->as('admin.')->prefix('admin')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('index');
    Route::resource('books', AdminBookController::class)->except('show');
    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::resource('users', AdminUsersController::class)->only(['index', 'edit', 'update', 'destroy']);
});

require __DIR__ . '/auth.php';
