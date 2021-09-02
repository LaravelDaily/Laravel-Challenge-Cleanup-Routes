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
Route::prefix('book')->name('books.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::resource('', BookController::class, ['only' => ['create', 'store']]);
        Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
        Route::post('{book}/report', [BookReportController::class, 'store'])->name('report.store');
    });
    Route::get('book/{book:slug}', [BookController::class, 'show'])->name('show');
});

Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::prefix('books')->name('books.')->group(function() {
        Route::get('', [BookController::class, 'index'])->name('list');
        Route::get('{book:slug}/edit', [BookController::class, 'edit'])->name('edit');
        Route::put('{book:slug}', [BookController::class, 'update'])->name('update');
        Route::delete('{book}', [BookController::class, 'destroy'])->name('destroy');
    });
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::prefix('settings')->group(function() {
        Route::get('', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
    });
});

Route::prefix('admin')->name('admin.')->middleware('isAdmin')->group(function () {
    Route::get('', AdminDashboardController::class)->name('index');
    Route::resource('users', AdminUsersController::class, ['only' => ['index', 'edit', 'update', 'destroy']]);
    Route::resource('books', AdminBookController::class);
    Route::put('books/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
});

require __DIR__ . '/auth.php';
