<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    BookController,
    BookReportController,
    UserSettingsController,
    UserChangePassword,
    OrderController
};
//use App\Http\Controllers\Admin\;
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
Route::prefix('book')->name('books.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('create', [BookController::class, 'create'])->name('create');
        Route::post('store', [BookController::class, 'store'])->name('store');
        Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
        Route::post('{book}/report', [BookReportController::class, 'store'])->name('report.store');
    });
    Route::get('{book:slug}', [BookController::class, 'show'])->name('show');
});

Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
        Route::resource('books', BookController::class)->only(['index', 'edit', 'update', 'destroy'])
            ->scoped(['book' => 'slug'])->names(['index' => 'books.list']);
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

        Route::resource('settings', UserSettingsController::class)->only(['index', 'update'])->parameters([
           'setting' => 'user'
        ])->names([
            'index' => 'settings'
        ]);
        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
});

require __DIR__ . '/auth.php';
