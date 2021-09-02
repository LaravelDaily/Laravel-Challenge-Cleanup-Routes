<?php

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\UserSettingsController;
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

Route::group(['prefix' => 'book', 'as' => 'books.', 'middleware' => 'auth'], function (){
    Route::get('create', [\App\Http\Controllers\BookController::class, 'create'])->name('create');
    Route::post('store', [\App\Http\Controllers\BookController::class, 'store'])->name('store');
    Route::get('{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->name('report.create');
    Route::post('{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->name('report.store');
    Route::get('{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->name('show')->withoutMiddleware('auth');

});

Route::group(['middleware' => 'auth', 'prefix' => 'user', 'as' => 'user.'], function(){

    Route::group(['prefix' => 'books', 'as' => 'books.'], function(){
        Route::get('/', [\App\Http\Controllers\BookController::class, 'index'])->name('list');
        Route::get('{book:slug}/edit', [\App\Http\Controllers\BookController::class, 'edit'])->name('edit');
        Route::put('{book:slug}', [\App\Http\Controllers\BookController::class, 'update'])->name('update');
        Route::delete('{book}', [\App\Http\Controllers\BookController::class, 'destroy'])->name('destroy');
    });

    Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');

    Route::prefix('settings')->group(function (){
        Route::get('/', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('settings');
        Route::post('{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('password.update');
    });


});

Route::group(['middleware' => 'isAdmin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('admin', AdminDashboardController::class)->name('index');
    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::resource('books', AdminBookController::class);
    Route::resource('users', AdminUsersController::class);
});

require __DIR__ . '/auth.php';
