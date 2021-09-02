<?php

use Illuminate\Support\Facades\Route;

Route::get('/', AdminDashboardController::class)->name('index');
// For some reason (most likely a bug), namespace is not taken into account unless uses() is used to specify the Controller
Route::put('book/approve/{book}')->uses([AdminBookController::class, 'approveBook'])->name('books.approve');
Route::resource('books', AdminBookController::class);
Route::resource('users', AdminUsersController::class)->except(['create', 'store']);
