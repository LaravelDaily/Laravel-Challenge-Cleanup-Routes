<?php

namespace Routes;

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\UserSettingsController;
use App\Models\BookReport;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\FuncCall;

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

// Middleware for books is handled at the controller level for finer control
Route::resource('books', BookController::class)
    ->only(['create', 'store', 'show'])
    ->scoped(['book' => 'slug']);

Route::middleware('auth')
    ->group(function () {
        Route::resource('books.report', BookReportController::class)
            ->only(['create', 'store'])
            ->scoped(['book' => 'slug']);

        Route::prefix('user/')->name('user.')
            ->group(function () {
                Route::resource('books', BookController::class)
                    ->only(['index', 'edit', 'update', 'destroy'])
                    ->scoped(['book' => 'slug']);

                Route::resource('orders', OrderController::class)->only('index');

                Route::resource('settings', UserSettingsController::class)
                    ->only(['index', 'update'])
                    ->parameters(['settings' => 'user']);

                Route::post('password/change/{user}', [UserChangePassword::class, 'update'])
                    ->name('password.update');
        });
    });

Route::middleware('isAdmin')->prefix('admin/')->name('admin.')
    ->group(function () {
        Route::get('', AdminDashboardController::class)->name('index');

        Route::resource('books', AdminBookController::class);
        Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])
            ->name('books.approve');

        Route::resource('users', AdminUsersController::class)
            ->only(['index', 'edit', 'update', 'destroy']);
    });

require __DIR__ . '/auth.php';
