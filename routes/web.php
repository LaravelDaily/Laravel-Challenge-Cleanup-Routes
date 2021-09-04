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

Route::group(['prefix' => 'book'], function(){
    Route::group(['middleware' => ['auth']], function(){
        Route::get('create', [\App\Http\Controllers\BookController::class, 'create'])->name('books.create');
        Route::post('store', [\App\Http\Controllers\BookController::class, 'store'])->name('books.store');
        Route::get('{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->name('books.report.create');
        Route::post('{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->name('books.report.store');
    });
    Route::get('{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->name('books.show');
});

Route::group(['middleware' => 'auth'], function(){
    Route::resource('user/books', \App\Http\Controllers\BookController::class);
    
    Route::get('user/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('user.orders.index');
    Route::get('user/settings', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('user.settings');
    Route::post('user/settings/{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('user.settings.update');
    Route::post('user/settings/password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('user.password.update');
});

Route::get('admin', \App\Http\Controllers\Admin\AdminDashboardController::class)->middleware('isAdmin')->name('admin.index');
Route::put('admin/book/approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->middleware('isAdmin')->name('admin.books.approve');

Route::resouce('admin/books', \App\Http\Controllers\Admin\AdminBookController::class);
Route::resource('admin/users',\App\Http\Controllers\Admin\AdminUsersController::class);

require __DIR__ . '/auth.php';
