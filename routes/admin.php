<?php

use Illuminate\Support\Facades\Route;

Route::get('/', AdminDashboardController::class)->name('index');
Route::put('book/approve/{book}')->uses([AdminBookController::class, 'approveBook'])->name('books.approve');
Route::resource('books', AdminBookController::class);
Route::resource('users', AdminUsersController::class)->except(['create', 'store']);
