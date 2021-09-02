<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\{
    AdminBookController,
    AdminDashboardController,
    AdminUsersController
};

/*
 * Routes accessible by authenticated administrators.
 *
 * middleware: 'web', 'isAdmin'
 * prefix: 'admin'
 * name: 'admin.'
 */

Route::get('/', AdminDashboardController::class)->name('index');

Route::resource("books", AdminBookController::class)->except(['show']);
Route::put('books/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');

Route::resource("users", AdminUsersController::class)->only(['index', 'edit', 'update', 'destroy']);
