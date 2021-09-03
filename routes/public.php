<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BookController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');

Route::get('/book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::as('password.')->group(function () {
        Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('request');
        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('email');
        Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('reset');
        Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('update');
    });
});
