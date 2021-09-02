<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'isAdmin', 'prefix' => 'admin'], function () {
    Route::get('/', AdminDashboardController::class)->name('admin.index');
    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('admin.books.approve');
    Route::prefix('books')->group(function () {
        Route::get('/', [AdminBookController::class, 'index'])->name('admin.books.index');
        Route::get('create', [AdminBookController::class, 'create'])->name('admin.books.create');
        Route::post('/', [AdminBookController::class, 'store'])->name('admin.books.store');
        Route::get('{book}/edit', [AdminBookController::class, 'edit'])->name('admin.books.edit');
        Route::put('{book}', [AdminBookController::class, 'update'])->name('admin.books.update');
        Route::delete('{book}', [AdminBookController::class, 'destroy'])->name('admin.books.destroy');
    });
    Route::prefix('users')->group(function () {
        Route::get('/', [AdminUsersController::class, 'index'])->name('admin.users.index');
        Route::get('{user}/edit', [AdminUsersController::class, 'edit'])->name('admin.users.edit');
        Route::put('{user}', [AdminUsersController::class, 'update'])->name('admin.users.update');
        Route::delete('{user}', [AdminUsersController::class, 'destroy'])->name('admin.users.destroy');
    });
});
