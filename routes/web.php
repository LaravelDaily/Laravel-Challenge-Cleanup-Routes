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

Route::middleware('auth')->group(function () {
  Route::group(['prefix' => 'book', 'as' => 'books.'], function () {
    Route::controller(BookController::class)->group(function () {
      Route::get('/create', 'create')->name('create');
      Route::post('/store', 'store')->name('store');
      Route::get('/{book:slug}', 'show')->name('show')->withoutMiddleware('auth');
    });
    Route::group(['controller' => BookReportController::class, 'as' => 'report.'], function () {
      Route::get('/{book:slug}/report/create', 'create')->name('create');
      Route::post('/{book}/report', 'store')->name('store');
    });
  });
  Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
    Route::group(['prefix' => 'books', 'controller' => BookController::class, 'as' => 'books.'], function () {
      Route::get('/books', 'index')->name('list');
      Route::get('/books/{book:slug}/edit', 'edit')->name('edit');
      Route::put('/books/{book:slug}', 'update')->name('update');
      Route::delete('/books/{book}', 'destroy')->name('destroy');
    });
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    Route::group(['controller' => UserSettingsController::class, 'prefix' => 'settings', 'as' => 'settings'], function () {
      Route::get('/', 'index');
      Route::post('/{user}', 'update')->name('.update');
    });
  });
  Route::post('/password/change/{user}', [UserChangePassword::class, 'update'])->name('user.password.update');
});

Route::group(['middleware' => ['isAdmin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
  Route::get('/', AdminDashboardController::class)->name('index');
  Route::resource('books',AdminBookController::class)->except('show');
  Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
  Route::resource('users', AdminUsersController::class)->except(['create', 'store', 'show']);
});

require __DIR__ . '/auth.php';
