<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\Admin\AdminDashboardController::class)->name('index');

Route::resource('books',\App\Http\Controllers\Admin\AdminBookController::class);
Route::put('book/approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->name('books.approve');

Route::resource('users',\App\Http\Controllers\Admin\AdminUsersController::class)->only(['index','edit','update','destroy']);
