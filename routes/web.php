<?php

use App\Http\Controllers\{
    Admin\AdminBookController,
    Admin\AdminDashboardController,
    Admin\AdminUsersController,
    BookController,
    BookReportController,
    HomeController,
    OrderController,
    UserChangePassword,
    UserSettingsController
};
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::prefix('book')->name('books.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('create', [BookController::class, 'create'])->name('create');
        Route::post('store', [BookController::class, 'store'])->name('store');

        Route::prefix('{book:slug}')->group(function () {
            Route::get('', [BookController::class, 'show'])->withoutMiddleware('auth')->name('show');

            Route::prefix('report')->name('report.')->group(function () {
                Route::post('', [BookReportController::class, 'store'])->name('store');
                Route::get('create', [BookReportController::class, 'create'])->name('create');
            });
        });
    });
});


Route::prefix('user')->name('user.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

        Route::prefix('books')->name('books.')->group(function () {
            Route::get('', [BookController::class, 'index'])->name('list');

            Route::prefix('{book:slug}')->group(function () {
                Route::put('', [BookController::class, 'update'])->name('update');
                Route::delete('', [BookController::class, 'destroy'])->name('destroy');
                Route::get('edit', [BookController::class, 'edit'])->name('edit');
            });
        });

        Route::prefix('settings')->group(function () {
            Route::get('', [UserSettingsController::class, 'index'])->name('settings');

            Route::prefix('{user}')->group(function () {
                Route::post('', [UserSettingsController::class, 'update'])->name('settings.update');
                Route::post('password/change', [UserChangePassword::class, 'update'])->name('password.update');
            });
        });
    });
});

Route::prefix('admin')->middleware('isAdmin')->name('admin.')->group(function () {
    Route::get('', AdminDashboardController::class)->name('index');

    Route::prefix('books')->name('books.')->group(function () {
        Route::get('', [AdminBookController::class, 'index'])->name('index');
        Route::post('', [AdminBookController::class, 'store'])->name('store');
        Route::get('create', [AdminBookController::class, 'create'])->name('create');

        Route::prefix('{book}')->group(function () {
            Route::put('', [AdminBookController::class, 'update'])->name('update');
            Route::delete('', [AdminBookController::class, 'destroy'])->name('destroy');
            Route::get('edit', [AdminBookController::class, 'edit'])->name('edit');
            Route::put('approve', [AdminBookController::class, 'approveBook'])->name('approve');
        });
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('', [AdminUsersController::class, 'index'])->name('index');

        Route::prefix('{user}')->group(function () {
            Route::put('', [AdminUsersController::class, 'update'])->name('update');
            Route::delete('', [AdminUsersController::class, 'destroy'])->name('destroy');
            Route::get('edit', [AdminUsersController::class, 'edit'])->name('edit');
        });
    });
});

require __DIR__ . '/auth.php';
