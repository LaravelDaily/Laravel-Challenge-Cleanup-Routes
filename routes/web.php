<?php

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

Route::middleware(['auth'])->group(function () {
    Route::name('books.')->group(function () {
        Route::get('book/create', [\App\Http\Controllers\BookController::class, 'create'])->name('create');
        Route::post('book/store', [\App\Http\Controllers\BookController::class, 'store'])->name('store');
        Route::get('book/{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->name('report.create');
        Route::post('book/{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->name('report.store');
        Route::get('book/{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->withoutMiddleware('auth')->name('show');
    });

    Route::name('user.')->group(function () {
        Route::get('user/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
        Route::get('user/settings', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('settings');
        Route::post('user/settings/{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('user/settings/password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('password.update');

        Route::name('books.')->group(function () {
            Route::get('user/books', [\App\Http\Controllers\BookController::class, 'index'])->name('list');
            Route::get('user/books/{book:slug}/edit', [\App\Http\Controllers\BookController::class, 'edit'])->name('edit');
            Route::put('user/books/{book:slug}', [\App\Http\Controllers\BookController::class, 'update'])->name('update');
            Route::delete('user/books/{book}', [\App\Http\Controllers\BookController::class, 'destroy'])->name('destroy');
        });
    });
});

Route::middleware(['isAdmin'])->group(function () {
    Route::name('admin.')->group(function () {
        Route::get('admin', \App\Http\Controllers\Admin\AdminDashboardController::class)->name('index');

        Route::name('books.')->group(function () {
            Route::get('admin/books', [\App\Http\Controllers\Admin\AdminBookController::class, 'index'])->name('index');
            Route::get('admin/books/create', [\App\Http\Controllers\Admin\AdminBookController::class, 'create'])->name('create');
            Route::post('admin/books', [\App\Http\Controllers\Admin\AdminBookController::class, 'store'])->name('store');
            Route::get('admin/books/{book}/edit', [\App\Http\Controllers\Admin\AdminBookController::class, 'edit'])->name('edit');
            Route::put('admin/books/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'update'])->name('update');
            Route::delete('admin/books/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'destroy'])->name('destroy');
            Route::put('admin/book/approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->name('approve');
        });

        Route::name('users.')->group(function () {
            Route::get('admin/users', [\App\Http\Controllers\Admin\AdminUsersController::class, 'index'])->name('index');
            Route::get('admin/users/{user}/edit', [\App\Http\Controllers\Admin\AdminUsersController::class, 'edit'])->name('edit');
            Route::put('admin/users/{user}', [\App\Http\Controllers\Admin\AdminUsersController::class, 'update'])->name('update');
            Route::delete('admin/users/{user}', [\App\Http\Controllers\Admin\AdminUsersController::class, 'destroy'])->name('destroy');
        });
    });
});

require __DIR__ . '/auth.php';
