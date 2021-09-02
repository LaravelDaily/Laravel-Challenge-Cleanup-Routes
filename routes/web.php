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

Route::middleware('auth')->group(function (){
    Route::resource('books', BookController::class)->only('create', 'store');
    Route::resource('books.report', BookReportController::class)->only(['create', 'store'])->scoped(['book' => 'slug'])->shallow();

    Route::prefix('user')->name('user.')->group(function () {
        Route::resource('books', BookController::class)->only(['index', 'edit', 'update'])->scoped(['book' => 'slug'])->names(['index' => 'books.list']);
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/settings', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('/settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
    });
});

Route::get('books/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::prefix('admin')->name('admin.')->middleware('isAdmin')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('index');
    Route::put('/books/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::resource('books', AdminBookController::class)->except('show');
    Route::resource('users', AdminUsersController::class)->only(['index', 'edit', 'update', 'destroy']);
});

require __DIR__ . '/auth.php';
