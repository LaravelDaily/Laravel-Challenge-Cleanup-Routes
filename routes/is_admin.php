<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {
    Route::get('/', AdminDashboardController::class)->name('index');

    Route::resource('books',AdminBookController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])
        ->name('books.approve');

    Route::resource('users',AdminUsersController::class)
        ->only(['index', 'edit', 'update', 'destroy']);
});
