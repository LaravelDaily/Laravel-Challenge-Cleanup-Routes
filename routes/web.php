<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    BookController,
    HomeController
};

/*
 * Routes that do not require authentication.
 *
 * middleware: 'web'
 */

Route::get('/', HomeController::class)->name('home');
Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');
