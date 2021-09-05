<?php


// Admin Controllers
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

// Home route
Route::get('/', HomeController::class)->name('home');

// Auth routes
Route::middleware('auth')->group(function () {

    // Books routes [ auth ]
    Route::prefix('books')->group(function () {
        Route::get('book/create', [BookController::class, 'create'])->name('create');
        Route::post('book/store', [BookController::class, 'store'])->name('store');
        Route::get('book/{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
        Route::post('book/{book}/report', [BookReportController::class, 'store'])->name('report.store');
        Route::get('book/{book:slug}', [BookController::class, 'show'])->name('show');
    });

    // User routes [ auth ]
    Route::prefix('user')->group(function () {
        Route::get('user/books', [BookController::class, 'index'])->name('user.books.list');
        Route::get('user/books/{book:slug}/edit', [BookController::class, 'edit'])->name('user.books.edit');
        Route::put('user/books/{book:slug}', [BookController::class, 'update'])->name('user.books.update');
        Route::delete('user/books/{book}', [BookController::class, 'destroy'])->name('user.books.destroy');
        Route::get('user/orders', [OrderController::class, 'index'])->name('user.orders.index');
        Route::get('user/settings', [UserSettingsController::class, 'index'])->name('user.settings');
        Route::post('user/settings/{user}', [UserSettingsController::class, 'update'])->name('user.settings.update');
        Route::post('user/settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('user.password.update');
    });
});

// Admin routes
Route::prefix('admin')->middleware('auth')->group(function () {
    // Dashboard route [ admin ]
    Route::get('admin', AdminDashboardController::class)->name('index');

    // Books routes [ admin ]
    Route::get('admin/books', [AdminBookController::class, 'index'])->name('books.index');
    Route::get('admin/books/create', [AdminBookController::class, 'create'])->name('books.create');
    Route::post('admin/books', [AdminBookController::class, 'store'])->name('books.store');
    Route::get('admin/books/{book}/edit', [AdminBookController::class, 'edit'])->name('books.edit');
    Route::put('admin/books/{book}', [AdminBookController::class, 'update'])->name('books.update');
    Route::delete('admin/books/{book}', [AdminBookController::class, 'destroy'])->name('books.destroy');
    Route::put('admin/book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');

    // Users routes [ admin ]
    Route::get('admin/users', [AdminUsersController::class, 'index'])->name('users.index');
    Route::get('admin/users/{user}/edit', [AdminUsersController::class, 'edit'])->name('users.edit');
    Route::put('admin/users/{user}', [AdminUsersController::class, 'update'])->name('users.update');
    Route::delete('admin/users/{user}', [AdminUsersController::class, 'destroy'])->name('users.destroy');
});

// Require laravel auth file
require __DIR__ . '/auth.php';
