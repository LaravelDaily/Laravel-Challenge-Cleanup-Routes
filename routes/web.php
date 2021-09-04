<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Auth;
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
Auth::routes();

Route::get('/', HomeController::class)->name('home');

Route::middleware('auth')->group(function () {


    Route::prefix('book')->name("books.")->group(function () {
        Route::get('/create', [BookController::class, 'create'])->name('create');
        Route::post('/store', [BookController::class, 'store'])->name('store');
        Route::get('/{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
        Route::post('/{book}/report', [BookReportController::class, 'store'])->name('report.store');
        Route::get('/{book:slug}', [BookController::class, 'show'])->name('show')->withoutMiddleware("auth");

    });

    Route::prefix('user')->name("user.")->group(function () {

        Route::prefix('book')->name("books.")->group(function () { 
            Route::get('/', [BookController::class, 'index'])->name('list');
            Route::get('/{book:slug}/edit', [BookController::class, 'edit'])->name('edit');
            Route::put('/{book:slug}', [BookController::class, 'update'])->name('update');
            Route::delete('/{book}', [BookController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('settings')->group(function () { 
            Route::get('/', [UserSettingsController::class, 'index'])->name('settings');
            Route::post('/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
            Route::post('/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
        });
        
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    });


});



Route::middleware('isAdmin')->prefix('admin')->name("admin.")->group(function () {
        
        Route::get('/', AdminDashboardController::class)->name('index');
    
        Route::resource('books', AdminBookController::class);        
        Route::put('/book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    
        Route::resource('users', AdminUsersController::class);

});



