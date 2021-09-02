<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;

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

Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::group(['prefix'=>'books', 'middleware' => 'auth', 'as'=>'books.'], function(){
    Route::resource('/', BookController::class)->only(['create', 'store']);
    Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
    Route::post('{book}/report', [BookReportController::class, 'store'])->name('report.store');
});


Route::group(['prefix' => 'user', 'middleware' => 'auth', 'as' => 'user.'], function(){
    Route::resource('/books', BookController::class)->only(['index', 'destroy'])->names([
        'index' => 'books.list'
    ]);

    Route::get('books/{book:slug}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('books/{book:slug}', [BookController::class, 'update'])->name('books.update');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

    Route::get('settings', [UserSettingsController::class, 'index'])->name('settings');
    Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
});

Route::group(['prefix' => 'admin', 'middleware' => 'isAdmin', 'as' => 'admin.'], function(){
    Route::get('/', AdminDashboardController::class)->name('index');
    Route::resource('books', AdminBookController::class)->except('show');
    Route::put('admin/book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::resource('users', AdminUsersController::class)->except(['create', 'store']);
});



require __DIR__ . '/auth.php';
