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

Route::prefix("books")->name("books.")->group(function (){
    Route::get('/', [AdminBookController::class, 'index'])->name('index');
    Route::get('create', [AdminBookController::class, 'create'])->name('create');
    Route::post('/', [AdminBookController::class, 'store'])->name('store');
    Route::get('{book}/edit', [AdminBookController::class, 'edit'])->name('edit');
    Route::put('{book}', [AdminBookController::class, 'update'])->name('update');
    Route::delete('{book}', [AdminBookController::class, 'destroy'])->name('destroy');
    Route::put('approve/{book}', [AdminBookController::class, 'approveBook'])->name('approve');
});

Route::prefix("users")->name("users.")->group(function (){
    Route::get('/', [AdminUsersController::class, 'index'])->name('index');
    Route::get('{user}/edit', [AdminUsersController::class, 'edit'])->name('edit');
    Route::put('{user}', [AdminUsersController::class, 'update'])->name('update');
    Route::delete('{user}', [AdminUsersController::class, 'destroy'])->name('destroy');
});
