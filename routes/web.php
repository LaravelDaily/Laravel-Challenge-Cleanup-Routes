<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
  HomeController,
  BookController,
  BookReportController,
  OrderController,
  UserSettingsController,
  UserChangePassword
};

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
  Route::resource('book', BookController::class)->only(['create', 'store'])->names([
    'create' => 'books.create',
    'store' => 'books.store'
  ]);
  Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show')->withoutMiddleware('auth');

  Route::resource('book.report', BookReportController::class)->only(['create', 'store'])->names([
    'create' => 'books.report.create',
    'store' => 'books.report.store',
  ])->scoped(['book' => 'slug']);

  Route::prefix('user/')->name('user.')->group(function () {
    Route::resource('books', BookController::class)->except(['create', 'store', 'show'])->names([
      'index' => 'books.list'
    ])->scoped(['book' => 'slug']);

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

    Route::get('user/settings', [UserSettingsController::class, 'index'])->name('settings');
    Route::post('user/settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
  });
});

require __DIR__ . '/auth.php';
