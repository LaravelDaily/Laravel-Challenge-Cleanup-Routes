<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;

Route::get('/', AdminDashboardController::class)->name('admin.index');

Route::resource('user', AdminUsersController::class)->names([
    'index' => 'admin.users.index',
    'edit' => 'admin.users.edit',
    'update' => 'admin.users.update',
    'destroy' => 'admin.users.destroy',
]);

Route::resource('book', AdminBookController::class)->names([
    'index' => 'admin.books.index',
    'create' => 'admin.books.create',
    'store' => 'admin.books.store',
    'edit' => 'admin.books.edit',
    'update' => 'admin.books.update',
    'destroy' => 'admin.books.destroy'
]);

Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('admin.books.approve');

require __DIR__ . '/auth.php';
