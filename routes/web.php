<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    BookController,
    BookReportController,
    OrderController,
    UserSettingsController,
    UserChangePassword,
    Admin\AdminDashboardController,
    Admin\AdminBookController,
    Admin\AdminUsersController,
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

Route::view('/', 'front.home')->name('home');

Route::middleware('auth')->group(function () {
    Route::prefix('/book')->name('books.')->group(function () {
        Route::get('/create', [BookController::class, 'create'])->name('create');
        Route::post('/store', [BookController::class, 'store'])->name('store');
        Route::get('/{book:slug}', [BookController::class, 'show'])->withoutMiddleware('auth')->name('show');

        Route::name('report.')->group(function () {
            Route::post('/{book}/report', [BookReportController::class, 'store'])->name('store');
            Route::get('/{book:slug}/report/create', [BookReportController::class, 'create'])->name('create');
        });
    });

    Route::prefix('/users')->name('user.')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

        Route::get('/settings', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('/settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');

        Route::resource('books', BookController::class)
            ->only(['index', 'edit', 'update', 'destroy'])
            ->scoped(['book' => 'slug'])
            ->names(['index' => 'books.list']);
    });
});


Route::prefix('/admin')->name('admin.')->middleware('isAdmin')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('index');

    Route::resource('books', AdminBookController::class);
    Route::put('/books/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');

    Route::resource('users', AdminUsersController::class);
});

require __DIR__ . '/auth.php';
