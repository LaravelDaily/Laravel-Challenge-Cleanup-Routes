<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

Route::middleware(['auth'])->group(function () {

    Route::resource('book', \App\Http\Controllers\BookController::class, ['names' => 'books'])->only(['create', 'store']);

    Route::get('book/{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->name('books.report.create');
    Route::post('book/{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->name('books.report.store');

    Route::group(['prefix' => 'user', 'as' => 'user.'], function() {

        Route::resource('books', \App\Http\Controllers\BookController::class, ['names' => ['index' => 'books.list']])
        ->only(['index', 'edit', 'update', 'destroy'])->parameters(['books' => 'book:slug']);

        Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
        
        Route::get('settings', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('settings');
        Route::post('settings/{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('settings.update');

        Route::post('settings/password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('password.update');
    });
});

Route::get('book/{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->name('books.show');

Route::group(['prefix' => 'admin', 'middleware' => 'isAdmin', 'as' => 'admin.'], function () {

    Route::get('/', \App\Http\Controllers\Admin\AdminDashboardController::class)->name('index');
    
    Route::resource('books', \App\Http\Controllers\Admin\AdminBookController::class)->except('show');

    Route::put('book/approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->name('books.approve');
    
    Route::resource('users', \App\Http\Controllers\Admin\AdminUsersController::class)->only('index', 'edit', 'update', 'destroy');

});


require __DIR__ . '/auth.php';
