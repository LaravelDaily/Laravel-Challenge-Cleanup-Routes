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

Route::middleware(['auth'])->group(function () {
    Route::prefix('book')->group(function () {
        Route::name('books.')->group(function () {
            Route::get('/create', [BookController::class, 'create'])->name('create');
            Route::post('/store', [BookController::class, 'store'])->name('store');

            Route::name('report.')->group(function () {
                Route::get('/{book:slug}/report/create', [BookReportController::class, 'create'])->name('create');
                Route::post('/{book}/report', [BookReportController::class, 'store'])->name('store');
            });
        });
    });

    Route::prefix('user')->group(function () {
        Route::prefix('books')->group(function () {
            Route::name('user.books.')->group(function () {
                Route::get('/', [BookController::class, 'index'])->name('list');
                Route::get('/{book:slug}/edit', [BookController::class, 'edit'])->name('edit');
                Route::put('/{book:slug}', [BookController::class, 'update'])->name('update');
                Route::delete('/{book}', [BookController::class, 'destroy'])->name('destroy');
            });
        });

        Route::get('/orders', [OrderController::class, 'index'])->name('user.orders.index');

        Route::prefix('settings')->group(function () {
            Route::name('user.')->group(function () {
                Route::name('settings')->group(function () {
                    Route::get('/', [UserSettingsController::class, 'index']);
                    Route::post('/{user}', [UserSettingsController::class, 'update'])->name('.update');
                });

                Route::post('/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
            });
        });
    });
});

Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::middleware(['isAdmin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::name('admin.')->group(function () {
            Route::get('/', AdminDashboardController::class)->name('index');

            Route::name('books.')->group(function () {
                Route::prefix('books')->group(function () {
                    Route::get('/', [AdminBookController::class, 'index'])->name('index');
                    Route::get('/create', [AdminBookController::class, 'create'])->name('create');
                    Route::post('/', [AdminBookController::class, 'store'])->name('store');
                    Route::get('/{book}/edit', [AdminBookController::class, 'edit'])->name('edit');
                    Route::put('/{book}', [AdminBookController::class, 'update'])->name('update');
                    Route::delete('/{book}', [AdminBookController::class, 'destroy'])->name('destroy');
                });

                Route::put('/book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('approve');
            });

            Route::prefix('users')->group(function () {
                Route::name('users.')->group(function () {
                    Route::get('/', [AdminUsersController::class, 'index'])->name('index');
                    Route::get('/{user}/edit', [AdminUsersController::class, 'edit'])->name('edit');
                    Route::put('/{user}', [AdminUsersController::class, 'update'])->name('update');
                    Route::delete('/{user}', [AdminUsersController::class, 'destroy'])->name('destroy');
                });
            });
        });
    });
});

require __DIR__ . '/auth.php';
