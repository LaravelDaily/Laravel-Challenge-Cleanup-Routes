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

Route::middleware('auth')->group(function() {
    // Book operations
    Route::resource('books', BookController::class)->names([
        'index' => 'user.books.list',
        'edit' => 'user.books.edit',
        'update' => 'user.books.update',
        'destroy' => 'user.books.destroy',
    ]);

    // Book report operations
    Route::name('books.report.')->group(function() {
        Route::get('book/{book}/report/create', [BookReportController::class, 'create'])->name('create');
        Route::post('book/{book}/report', [BookReportController::class, 'store'])->name('store');
    });

    Route::prefix('user')->name('user.')->group(function() {
        // Orders
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

        // User settings
        Route::prefix('settings')->group(function() {
            Route::get('/', [UserSettingsController::class, 'index'])->name('settings');
            Route::post('{user}', [UserSettingsController::class, 'update'])->name('settings.update');
            Route::post('password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
        });
    });
});

require __DIR__ . '/auth.php';
