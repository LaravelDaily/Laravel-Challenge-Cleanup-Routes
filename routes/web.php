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

Route::get('book/{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->name('books.show');

Route::middleware(['auth'])->group(function (){
    Route::resource('books', \App\Http\Controllers\BookController::class)
        ->only(['create', 'store'])
        ->scoped(['book' => 'slug']);

    Route::resource('books.report', \App\Http\Controllers\BookReportController::class)
        ->only(['create', 'store'])
        ->scoped(['book' => 'slug']);;

    Route::prefix('user')->name('user.')->group(function (){
        Route::resource('books', \App\Http\Controllers\BookController::class)
            ->except(['create', 'store', 'show'])
            ->names(['index' => 'books.list'])
            ->scoped(['book' => 'slug']);

        Route::resource('orders', \App\Http\Controllers\OrderController::class)->only('index');

        Route::prefix('settings')->group(function (){
            Route::post('password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('password.update');
            Route::post('{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('settings.update');
        });

        Route::resource('settings', \App\Http\Controllers\UserSettingsController::class)
            ->names(['index' => 'settings'])
            ->only('index');
    });
});


Route::middleware(['isAdmin'])->prefix('admin')->name('admin.')->group(function (){
    Route::get('/', \App\Http\Controllers\Admin\AdminDashboardController::class)->name('index');

    Route::resource('books', \App\Http\Controllers\Admin\AdminBookController::class);
    Route::put('book/approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->name('books.approve');

    Route::resource('users', \App\Http\Controllers\Admin\AdminUsersController::class)
        ->only(['index', 'edit', 'update','destroy']);
});
require __DIR__ . '/auth.php';
