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

Route::middleware('auth')->group(function(){
  Route::get('book/create', [\App\Http\Controllers\BookController::class, 'create'])->name('books.create');
  Route::post('book/store', [\App\Http\Controllers\BookController::class, 'store'])->name('books.store');
  Route::get('book/{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->name('books.report.create');
  Route::post('book/{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->name('books.report.store');

  Route::get('user/books', [\App\Http\Controllers\BookController::class, 'index'])->name('user.books.list');
  Route::get('user/books/{book:slug}/edit', [\App\Http\Controllers\BookController::class, 'edit'])->name('user.books.edit');
  Route::put('user/books/{book:slug}', [\App\Http\Controllers\BookController::class, 'update'])->name('user.books.update');
  Route::delete('user/books/{book}', [\App\Http\Controllers\BookController::class, 'destroy'])->name('user.books.destroy');

  Route::get('user/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('user.orders.index');
  
  Route::get('user/settings', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('user.settings');
  Route::post('user/settings/{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('user.settings.update');
  Route::post('user/settings/password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('user.password.update');
});

Route::get('book/{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->name('books.show');


Route::middleware('isAdmin')->group(function(){
  Route::get('admin', \App\Http\Controllers\Admin\AdminDashboardController::class)->name('admin.index');
  
  Route::get('admin/books', [\App\Http\Controllers\Admin\AdminBookController::class, 'index'])->name('admin.books.index');
  Route::get('admin/books/create', [\App\Http\Controllers\Admin\AdminBookController::class, 'create'])->name('admin.books.create');
  Route::post('admin/books', [\App\Http\Controllers\Admin\AdminBookController::class, 'store'])->name('admin.books.store');
  Route::get('admin/books/{book}/edit', [\App\Http\Controllers\Admin\AdminBookController::class, 'edit'])->name('admin.books.edit');
  Route::put('admin/books/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'update'])->name('admin.books.update');
  Route::delete('admin/books/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'destroy'])->name('admin.books.destroy');
  Route::put('admin/book/approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->name('admin.books.approve');
  
  Route::get('admin/users', [\App\Http\Controllers\Admin\AdminUsersController::class, 'index'])->name('admin.users.index');
  Route::get('admin/users/{user}/edit', [\App\Http\Controllers\Admin\AdminUsersController::class, 'edit'])->name('admin.users.edit');
  Route::put('admin/users/{user}', [\App\Http\Controllers\Admin\AdminUsersController::class, 'update'])->name('admin.users.update');
  Route::delete('admin/users/{user}', [\App\Http\Controllers\Admin\AdminUsersController::class, 'destroy'])->name('admin.users.destroy');
});


require __DIR__ . '/auth.php';
