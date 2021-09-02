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
Route::post('user/settings/{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->middleware('auth')->name('user.settings.update');
Route::post('user/settings/password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->middleware('auth')->name('user.password.update');

require __DIR__ . '/auth.php';
