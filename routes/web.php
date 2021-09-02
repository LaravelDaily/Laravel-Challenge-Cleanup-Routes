<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{AdminBookController, AdminDashboardController, AdminUsersController};
use App\Http\Controllers\{BookController, BookReportController, OrderController, UserChangePassword, UserSettingsController};

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

Route::prefix('book')->group(function () {
    Route::middleware('auth')->group(function () {

        Route::get('/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/store', [BookController::class, 'store'])->name('books.store');
        Route::get('/{book:slug}/report/create', [BookReportController::class, 'create'])->name('books.report.create');
        Route::post('/{book}/report', [BookReportController::class, 'store'])->name('books.report.store');
    });

    Route::get('/{book:slug}', [BookController::class, 'show'])->name('books.show');
});

Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('/books', [BookController::class, 'index'])->name('user.books.list');
    Route::get('/books/{book:slug}/edit', [BookController::class, 'edit'])->name('user.books.edit');
    Route::put('/books/{book:slug}', [BookController::class, 'update'])->name('user.books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('user.books.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('user.orders.index');

    Route::get('/settings', [UserSettingsController::class, 'index'])->name('user.settings');
    Route::post('/settings/{user}', [UserSettingsController::class, 'update'])->name('user.settings.update');
    Route::post('/settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('user.password.update');
});

Route::middleware('isAdmin')->prefix('admin')->group(function () {
    Route::get('admin', AdminDashboardController::class)->name('admin.index');

    Route::get('/books', [AdminBookController::class, 'index'])->name('admin.books.index');
    Route::get('/books/create', [AdminBookController::class, 'create'])->name('admin.books.create');
    Route::post('/books', [AdminBookController::class, 'store'])->name('admin.books.store');
    Route::get('/books/{book}/edit', [AdminBookController::class, 'edit'])->name('admin.books.edit');
    Route::put('/books/{book}', [AdminBookController::class, 'update'])->name('admin.books.update');
    Route::delete('/books/{book}', [AdminBookController::class, 'destroy'])->name('admin.books.destroy');
    Route::put('/book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('admin.books.approve');

    Route::get('/users', [AdminUsersController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/edit', [AdminUsersController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminUsersController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminUsersController::class, 'destroy'])->name('admin.users.destroy');
});

require __DIR__ . '/auth.php';
