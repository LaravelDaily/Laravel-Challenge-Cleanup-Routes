<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminDashboardController;

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

Route::get('/', [HomeController::class, '__invoke'])->name('home');

Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::middleware(['auth'])->group(function () {
   
    Route::resource('books', BookController::class)->only(['create', 'store']);

    Route::resource('books.report', BookReportController::class)->only(['create', 'store'])->scoped(['book' => 'slug']);

    Route::group(['prefix' => 'user'], function() {
        Route::group(['prefix' => 'books'], function() { 
            Route::get('', [BookController::class, 'index'])->name('user.books.list');
            Route::get('{book:slug}/edit', [BookController::class, 'edit'])->name('user.books.edit');
            Route::put('{book:slug}', [BookController::class, 'update'])->name('user.books.update');
            Route::delete('{book}', [BookController::class, 'destroy'])->name('user.books.destroy');
        });

        Route::get('orders', [OrderController::class, 'index'])->name('user.orders.index');

        Route::group(['prefix' => 'settings'], function() { 
            Route::get('', [UserSettingsController::class, 'index'])->name('user.settings');
            Route::post('{user}', [UserSettingsController::class, 'update'])->name('user.settings.update');
            Route::post('password/change/{user}', [UserChangePassword::class, 'update'])->name('user.password.update');
        });
    });
});

Route::group(['middleware' => 'isAdmin', 'prefix' => 'admin'], function() {

    Route::get('admin', AdminDashboardController::class)->name('admin.index');

    Route::get('books', [AdminBookController::class, 'index'])->name('admin.books.index');
    Route::get('books/create', [AdminBookController::class, 'create'])->name('admin.books.create');
    Route::post('books', [AdminBookController::class, 'store'])->name('admin.books.store');
    Route::get('books/{book}/edit', [AdminBookController::class, 'edit'])->name('admin.books.edit');
    Route::put('books/{book}', [AdminBookController::class, 'update'])->name('admin.books.update');
    Route::delete('books/{book}', [AdminBookController::class, 'destroy'])->name('admin.books.destroy');
    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('admin.books.approve');
    
    Route::get('users', [AdminUsersController::class, 'index'])->name('admin.users.index');
    Route::get('users/{user}/edit', [AdminUsersController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/{user}', [AdminUsersController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [AdminUsersController::class, 'destroy'])->name('admin.users.destroy');
});

require __DIR__ . '/auth.php';