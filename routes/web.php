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

Route::middleware('auth')->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::name('books.')->group(function () {
            Route::get('books', [\App\Http\Controllers\BookController::class, 'index'])->name('list');
            Route::get('books/{book:slug}/edit', [\App\Http\Controllers\BookController::class, 'edit'])->name('edit');
            Route::put('books/{book:slug}', [\App\Http\Controllers\BookController::class, 'update'])->name('update');
            Route::delete('books/{book}', [\App\Http\Controllers\BookController::class, 'destroy'])->name('destroy');
        });
        Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
        Route::get('settings', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('settings');
        Route::post('settings/{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('password.update');
    });

    Route::prefix('book')->name('books.')->group(function () {
        Route::get('create', [\App\Http\Controllers\BookController::class, 'create'])->name('create');
        Route::post('store', [\App\Http\Controllers\BookController::class, 'store'])->name('store');

        Route::name('report.')->group(function () {
            Route::get('{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->name('create');
            Route::post('{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->name('store');
        });
    });
});

Route::get('book/{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->name('books.show');

Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', \App\Http\Controllers\Admin\AdminDashboardController::class)->name('index');
    Route::put('book/approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::prefix('books')->name('books.')->group(function () {
        Route::resource('/', \App\Http\Controllers\Admin\AdminBookController::class, [
            'parameters' => ['' => 'book'],
        ]);
    });
    Route::prefix('users')->name('users.')->group(function () {
        Route::resource('/', \App\Http\Controllers\Admin\AdminUsersController::class, [
            'except' => ['show', 'store'],
            'parameters' => ['' => 'user'],
        ]);
    });
});



require __DIR__ . '/auth.php';
