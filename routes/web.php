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
    Route::prefix('book')->name('books.')->group(function () {
        Route::get('create', [BookController::class, 'create'])->name('create');
        Route::post('store', [BookController::class, 'store'])->name('store');
        Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
        Route::post('{book}/report', [BookReportController::class, 'store'])->name('report.store');
    });
    

    Route::prefix('user')->name('user.')->group(function () {
        Route::resource('books', BookController::class)
            ->only(['index', 'destroy', 'edit', 'update'])
            ->names([
                'index' => 'books.list',
            ])
            ->scoped([
                'book' => 'slug'
            ]);

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        
        Route::prefix('settings')->group(function () {
            Route::get('/', [UserSettingsController::class, 'index'])->name('settings');
            Route::post('{user}', [UserSettingsController::class, 'update'])->name('settings.update');
            Route::post('password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
        });
    });
});

Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function() {
    Route::get('/', AdminDashboardController::class)->name('index');
    Route::resource('books', AdminBookController::class)->names('books');
    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::resource('users', AdminUsersController::class)
        ->except(['create', 'store', 'show'])
        ->names('users');
});

require __DIR__ . '/auth.php';
