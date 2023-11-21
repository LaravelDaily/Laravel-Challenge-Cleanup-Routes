<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    BookController,
    BookReportController,
    OrderController,
    UserChangePassword,
    UserSettingsController,
    Admin\AdminBookController,
    Admin\AdminUsersController,
    Admin\AdminDashboardController
};

Route::get('/', HomeController::class)->name('home');

Route::middleware(['auth'])->group(function () {

    Route::resource('book', BookController::class)->only(['create', 'store', 'show', 'update', 'destroy']);

    Route::resource('book.report', BookReportController::class)->only(['create', 'store'])->names([
        'create' => 'books.report.create',
        'store' => 'books.report.store',
    ])->parameters(['book' => 'book:slug']);

    Route::resource('user.books', BookController::class)->only(['index', 'edit', 'update', 'destroy'])->names([
        'index' => 'user.books.list',
        'edit' => 'user.books.edit',
        'update' => 'user.books.update',
        'destroy' => 'user.books.destroy',
    ])->parameters(['books' => 'book:slug']);

    Route::get('user/orders', [OrderController::class, 'index'])->name('user.orders.index');
    Route::get('user/settings', [UserSettingsController::class, 'index'])->name('user.settings');
    Route::post('user/settings/{user}', [UserSettingsController::class, 'update'])->name('user.settings.update');
    Route::post('user/settings/password/change/{user}', [UserChangePassword::class, 'update'])
        ->name('user.password.update');
});

Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('admin.index');

    Route::resource('books', AdminBookController::class)->except(['show'])->names([
        'index' => 'admin.books.index',
        'create' => 'admin.books.create',
        'store' => 'admin.books.store',
        'edit' => 'admin.books.edit',
        'update' => 'admin.books.update',
        'destroy' => 'admin.books.destroy',
    ]);

    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('admin.books.approve');

    Route::resource('users', AdminUsersController::class)->only(['index', 'edit', 'update', 'destroy'])->names([
        'index' => 'admin.users.index',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
});

require __DIR__ . '/auth.php';
