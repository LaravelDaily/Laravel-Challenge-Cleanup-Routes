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

// -------------- book -----------------------
Route::prefix('book')->name('books.')->group(function () {
    // -------------- must auth -----------------------
    Route::middleware('auth')->group(function () {
        Route::get('/create', [BookController::class, 'create'])->name('create');//books.create
        Route::post('/store', [BookController::class, 'store'])->name('store');//books.store
        Route::get('/{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');//books.report.create
        Route::post('/{book}/report', [BookReportController::class, 'store'])->name('report.store');//books.report.store
    });
    // -------------- should not auth -----------------------
    Route::get('/{book:slug}', [BookController::class, 'show'])->name('show');//books.show
});

// ------------- must auth - User -----------------------
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    // -------------- User books -----------------------
    Route::prefix('books')->name('books.')->group(function () {
        Route::get('/', [BookController::class, 'index'])->name('list');//user.books.list
        Route::get('/{book:slug}/edit', [BookController::class, 'edit'])->name('edit');//user.books.edit
        Route::put('/{book:slug}', [BookController::class, 'update'])->name('update');//user.books.update
        Route::delete('/{book}', [BookController::class, 'destroy'])->name('destroy');//user.books.destroy
    });
    // -------------- User orders -----------------------
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');//user.orders.index

    // -------------- User settings -----------------------
    Route::prefix('settings')->group(function () {
        Route::get('/', [UserSettingsController::class, 'index'])->name('settings');//user.settings
        Route::post('/{user}', [UserSettingsController::class, 'update'])->name('settings.update');//user.settings.update
        Route::post('/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');//user.password.update
    });
});


// ------------- must auth - Admin -----------------------
Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('index');//admin.index

    // -------------- admin books ---- *** route name (admin.books.{index,create,...}) *** -----------------
    Route::resource('books', AdminBookController::class);// route name (admin.books.{index,create,...})
    Route::put('/book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');//admin.books.approve

    // -------------- admin users ----------- *** route name (admin.users.{index,create,...}) *** ------------
    Route::resource('users', AdminUsersController::class)->only(['index', 'edit', 'update', 'destroy']);// route name (admin.users.{index,create,...})
});


require __DIR__ . '/auth.php';
