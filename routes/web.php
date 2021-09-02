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
Route::get('book/create', [\App\Http\Controllers\BookController::class, 'create'])->middleware('auth')->name('books.create');
Route::post('book/store', [\App\Http\Controllers\BookController::class, 'store'])->middleware('auth')->name('books.store');
Route::get('book/{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->middleware('auth')->name('books.report.create');
Route::post('book/{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->middleware('auth')->name('books.report.store');
Route::get('book/{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->name('books.show');

Route::get('user/books', [\App\Http\Controllers\BookController::class, 'index'])->middleware('auth')->name('user.books.list');
Route::get('user/books/{book:slug}/edit', [\App\Http\Controllers\BookController::class, 'edit'])->middleware('auth')->name('user.books.edit');
Route::put('user/books/{book:slug}', [\App\Http\Controllers\BookController::class, 'update'])->middleware('auth')->name('user.books.update');
Route::delete('user/books/{book}', [\App\Http\Controllers\BookController::class, 'destroy'])->middleware('auth')->name('user.books.destroy');

Route::get('user/orders', [\App\Http\Controllers\OrderController::class, 'index'])->middleware('auth')->name('user.orders.index');

Route::get('user/settings', [\App\Http\Controllers\UserSettingsController::class, 'index'])->middleware('auth')->name('user.settings');
Route::put('user/settings/{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->middleware('auth')->name('user.settings.update');
Route::put('user/settings/password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->middleware('auth')->name('user.password.update');

Route::get('admin', \App\Http\Controllers\Admin\AdminDashboardController::class)->middleware('isAdmin')->name('admin.index');

Route::get('admin/books', [\App\Http\Controllers\Admin\AdminBookController::class, 'index'])->middleware('isAdmin')->name('admin.books.index');
Route::get('admin/books/create', [\App\Http\Controllers\Admin\AdminBookController::class, 'create'])->middleware('isAdmin')->name('admin.books.create');
Route::post('admin/books', [\App\Http\Controllers\Admin\AdminBookController::class, 'store'])->middleware('isAdmin')->name('admin.books.store');
Route::get('admin/books/{book}/edit', [\App\Http\Controllers\Admin\AdminBookController::class, 'edit'])->middleware('isAdmin')->name('admin.books.edit');
Route::put('admin/books/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'update'])->middleware('isAdmin')->name('admin.books.update');
Route::delete('admin/books/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'destroy'])->middleware('isAdmin')->name('admin.books.destroy');
Route::put('admin/book/approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->middleware('isAdmin')->name('admin.books.approve');

Route::get('admin/users', [\App\Http\Controllers\Admin\AdminUsersController::class, 'index'])->middleware('isAdmin')->name('admin.users.index');
Route::get('admin/users/{user}/edit', [\App\Http\Controllers\Admin\AdminUsersController::class, 'edit'])->middleware('isAdmin')->name('admin.users.edit');
Route::put('admin/users/{user}', [\App\Http\Controllers\Admin\AdminUsersController::class, 'update'])->middleware('isAdmin')->name('admin.users.update');
Route::delete('admin/users/{user}', [\App\Http\Controllers\Admin\AdminUsersController::class, 'destroy'])->middleware('isAdmin')->name('admin.users.destroy');

require __DIR__ . '/auth.php';
