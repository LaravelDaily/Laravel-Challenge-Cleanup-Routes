<?php

use App\Http\Controllers\Admin\{
    AdminBookController,
    AdminDashboardController,
    AdminUsersController
};

use App\Http\Controllers\{
    BookController,
    BookReportController,
    HomeController,
    OrderController,
    UserChangePassword,
    UserSettingsController
};

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
Route::group(['middleware' => 'auth'], function () {
    Route::resource('books', BookController::class)->only(['create', 'store']);
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::resource('books', BookController::class)->only(['edit', 'update', 'destroy', 'index'])->names([
            'index' => 'books.list'
        ])->scoped([
            'book' => 'slug'
        ]);
        Route::resource('settings', UserSettingsController::class)->only('index')->names([
            'index' => 'settings'
        ]);
        Route::group(['prefix' => 'settings'], function(){
            Route::post('password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
            Route::post('{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        });
        Route::resource('orders', OrderController::class)->only(['index']);
    });
    Route::resource('books.report', BookReportController::class)->only(['create', 'store'])->scoped([
        'book' => 'slug'
    ])->shallow();

});
Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');
Route::group(['middleware' => 'isAdmin', 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('books', AdminBookController::class)->except('show');
    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');
    Route::resource('users', AdminUsersController::class)->except(['create', 'store', 'show']);
    Route::get('/', AdminDashboardController::class)->name('index');
});
require __DIR__ . '/auth.php';