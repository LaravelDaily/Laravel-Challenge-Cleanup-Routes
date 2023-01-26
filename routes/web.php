<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{AdminDashboardController, AdminBookController, AdminUsersController};
use App\Http\Controllers\{
    HomeController,
    BookController,
    BookReportController,
    OrderController,
    UserSettingsController,
    UserChangePassword,
};

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

Route::view('/', 'front.home')->name('home');

Route::middleware('auth')->group(function () {
    Route::prefix('/book')->name('books.')->group(function () {
        Route::get('/create', [BookController::class, 'create'])->name('create');
        Route::post('/store', [BookController::class, 'store'])->name('store');
        Route::get('/{book:slug}', [BookController::class, 'show'])->withoutMiddleware('auth')->name('show');

        Route::name('report.')->group(function () {
            Route::post('/{book}/report', [BookReportController::class, 'store'])->name('store');
            Route::get('/{book:slug}/report/create', [BookReportController::class, 'create'])->name('create');
        });
    });

    Route::prefix('/users')->name('user.')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

        Route::get('/settings', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('/settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');

        Route::prefix('/books')->name('books.')->group(function () {
            Route::get('/', [BookController::class, 'index'])->name('list');
            Route::get('/{book:slug}/edit', [BookController::class, 'edit'])->name('edit');
            Route::put('/{book:slug}', [BookController::class, 'update'])->name('update');
            Route::delete('/{book}', [BookController::class, 'destroy'])->name('destroy');
        });
    });
});


Route::prefix('/admin')->name('admin.')->middleware('isAdmin')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('index');

    Route::prefix('/books')->name('books.')->group(function () {
        Route::get('/', [AdminBookController::class, 'index'])->name('index');
        Route::get('/create', [AdminBookController::class, 'create'])->name('create');
        Route::post('/', [AdminBookController::class, 'store'])->name('store');
        Route::get('/{book}/edit', [AdminBookController::class, 'edit'])->name('edit');
        Route::put('/{book}', [AdminBookController::class, 'update'])->name('update');
        Route::delete('/{book}', [AdminBookController::class, 'destroy'])->name('destroy');
        Route::put('/approve/{book}', [AdminBookController::class, 'approveBook'])->name('approve');
    });

    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/', [AdminUsersController::class, 'index'])->name('index');
        Route::get('/{user}/edit', [AdminUsersController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminUsersController::class, 'update'])->name('update');
        Route::delete('/{user}', [AdminUsersController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__ . '/auth.php';
