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
Route::middleware(['auth'])->group(function() {

    Route::get('book/create', [BookController::class, 'create'])->name('books.create');
    Route::post('book/store', [BookController::class, 'store'])->name('books.store');
    Route::get('book/{book:slug}/report/create', [BookReportController::class, 'create'])->name('books.report.create');
    Route::post('book/{book}/report', [BookReportController::class, 'store'])->name('books.report.store');

    Route::prefix('user')->as('user.')->group(function() {
        Route::prefix('books')->as('books.')->group(function(){
            Route::get('/', [BookController::class, 'index'])->name('list');
            Route::get('{book:slug}/edit', [BookController::class, 'edit'])->name('edit');
            Route::put('{book:slug}', [BookController::class, 'update'])->name('update');
            Route::delete('{book}', [BookController::class, 'destroy'])->name('destroy');
        });
    
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    
        Route::get('settings', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
    
    });
});
Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::middleware(['isAdmin'])->prefix('admin')->as('admin.')->group(function() {
    Route::get('/', AdminDashboardController::class)->name('index');

    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::resource('books', AdminBookController::class)->except('show');
    Route::resource('users', AdminUsersController::class)->only('index','edit','update','destroy');
});
require __DIR__ . '/auth.php';
