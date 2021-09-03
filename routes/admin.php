<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminDashboardController;

    Route::get('/', AdminDashboardController::class)->name('index');
    Route::name('books.')->group(function () {
        Route::prefix('books')->group(function(){
            Route::get('/', [AdminBookController::class, 'index'])->name('index');
            Route::get('create', [AdminBookController::class, 'create'])->name('create');
            Route::post('books', [AdminBookController::class, 'store'])->name('store');
            Route::get('{book}/edit', [AdminBookController::class, 'edit'])->name('edit');
            Route::put('{book}', [AdminBookController::class, 'update'])->name('update');
            Route::delete('{book}', [AdminBookController::class, 'destroy'])->name('destroy');
        });
        Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('approve');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUsersController::class, 'index'])->name('index');
        Route::get('{user}/edit', [AdminUsersController::class, 'edit'])->name('edit');
        Route::put('{user}', [AdminUsersController::class, 'update'])->name('update');
        Route::delete('{user}', [AdminUsersController::class, 'destroy'])->name('destroy');
        
    });
    
