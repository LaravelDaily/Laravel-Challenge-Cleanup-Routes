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
  Route::get('book/create', [BookController::class, 'create'])->name('books.create');
  Route::post('book/store', [BookController::class, 'store'])->name('books.store');
  Route::get('book/{book:slug}/report/create', [BookReportController::class, 'create'])->name('books.report.create');
  Route::post('book/{book}/report', [BookReportController::class, 'store'])->name('books.report.store');

  Route::get('user/books', [BookController::class, 'index'])->name('user.books.list');
  Route::get('user/books/{book:slug}/edit', [BookController::class, 'edit'])->name('user.books.edit');
  Route::put('user/books/{book:slug}', [BookController::class, 'update'])->name('user.books.update');
  Route::delete('user/books/{book}', [BookController::class, 'destroy'])->name('user.books.destroy');

  Route::get('user/orders', [OrderController::class, 'index'])->name('user.orders.index');
  
  Route::get('user/settings', [UserSettingsController::class, 'index'])->name('user.settings');
  Route::post('user/settings/{user}', [UserSettingsController::class, 'update'])->name('user.settings.update');
  Route::post('user/settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('user.password.update');
});

Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');


Route::group([
  'middleware' => 'isAdmin',
  'prefix' => 'admin',
  'as' => 'admin.',
],function(){
  Route::get('/', AdminDashboardController::class)->name('index');
  Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
  Route::resource('books', AdminBookController::class);
  Route::resource('users', AdminUsersController::class)->except('create', 'store');
});


require __DIR__ . '/auth.php';
