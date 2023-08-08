<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    BookController,
    BookReportController,
    OrderController,
    UserSettingsController,
    UserChangePassword,
    Admin\AdminDashboardController,
    Admin\AdminBookController,
    Admin\AdminUsersController
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
    Route::prefix('name')->name('books.')->group(function() {
        Route::resource('', BookController::class)->only('create', 'store');
        Route::get('{book}', [BookController::class, 'show'])->name('show')->withoutMiddleware('auth');
    
        Route::resource('{book}/report', BookReportController::class, ['names' => 'report'])->only('create', 'store');
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::resource('books', BookController::class, ['names' => ['index' => 'books.list']])->except('create', 'store', 'show');

        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

        Route::prefix('settings')->name('settings')->group(function () {
            Route::get('', [UserSettingsController::class, 'index']);
            Route::post('{user}', [UserSettingsController::class, 'update'])->name('.update');
        });
        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
    });
});


Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('', AdminDashboardController::class)->name('index');

    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::resource('books', AdminBookController::class)->except('show');

    Route::resource('users', AdminUsersController::class)->except('create', 'store', 'show');
});

require __DIR__ . '/auth.php';
