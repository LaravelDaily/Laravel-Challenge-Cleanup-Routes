<?php

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\HomeController;
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

Route::get('/', HomeController::class)->name('home');

Route::middleware('auth')->group(function(){
  Route::get('book/{book:slug}/report/create', [BookReportController::class, 'create'])->name('books.report.create');
  Route::post('book/{book}/report', [BookReportController::class, 'store'])->name('books.report.store');
  Route::resource('book', BookController::class)->only(['create', 'store'])->names('books');

  Route::prefix('user')->group(function(){
    Route::resource('books', BookController::class, ['as' => 'user'])->except('create', 'store')->name('index', 'user.books.list');

    Route::name('user.')->group(function(){
      Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

      Route::prefix('settings')->group(function(){
        Route::get('/', [UserSettingsController::class, 'index'])->name('settings');
          Route::post('{user}', [UserSettingsController::class, 'update'])->name('settings.update');
          Route::post('password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
      });
    });
  });
});

Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::group(['middleware' => 'isAdmin','prefix' => 'admin','as' => 'admin.',],function(){
  Route::get('/', AdminDashboardController::class)->name('index');
  Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
  Route::resource('books', AdminBookController::class);
  Route::resource('users', AdminUsersController::class)->except('create', 'store');
});

require __DIR__ . '/auth.php';
