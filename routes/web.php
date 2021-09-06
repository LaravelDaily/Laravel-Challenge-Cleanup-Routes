<?php

use Illuminate\Http\Request;

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


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminDashboardController;

Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');



Route::group(['middleware' => 'auth'],function () {
    
    Route::resource('book', BookController::class)->only([
        'create','store'
    ])->names([
        'create' => 'books.create',
        'store'=> "books.store"
    ]);
    
    Route::group(['prefix' => 'book'],function () {
        
        Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('books.report.create');
        Route::post('{book}/report', [BookReportController::class, 'store'])->name('books.report.store');
    });


    Route::group(['prefix' => 'user'],function () {
        Route::get('books', [BookController::class, 'index'])->name('user.books.list');
        Route::get('books/{book:slug}/edit', [BookController::class, 'edit'])->name('user.books.edit');
        Route::put('books/{book:slug}', [BookController::class, 'update'])->name('user.books.update');
        Route::delete('books/{book}', [BookController::class, 'destroy'])->name('user.books.destroy');
        Route::get('orders', [OrderController::class, 'index'])->name('user.orders.index');
        Route::get('settings', [UserSettingsController::class, 'index'])->name('user.settings');
        Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('user.settings.update');
        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('user.password.update');

    });



});
Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');



Route::get('admin', AdminDashboardController::class)->middleware('isAdmin')->name('admin.index');

Route::group(['middleware' => 'isAdmin',],function () {

    Route::resource('admin/books', AdminBookController::class)->except([
        'show'
    ])->names([
            'index'=>'admin.books.index',
        'create' => 'admin.books.create',
        'store'=> "admin.books.store",
        // 'show'=>'admin.books.edit'
        'edit'=> "admin.books.edit",
        'update'=> "admin.books.update",
        'destroy'=> "admin.books.destroy",
    ])->missing(function (Request $request) {
        return Redirect::route('photos.index');
    });
    

    Route::resource('admin/users', AdminUsersController::class)->except([
        'create','store','show'
    ])->names([
            'index'=>'admin.users.index',
        'edit'=> "admin.users.edit",
        'update'=> "admin.users.update",
        'destroy'=> "admin.users.destroy",
    ])->missing(function (Request $request) {
        return Redirect::route('photos.index');
    });





});



Route::put('admin/book/approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->middleware('isAdmin')->name('admin.books.approve');



require __DIR__ . '/auth.php';
