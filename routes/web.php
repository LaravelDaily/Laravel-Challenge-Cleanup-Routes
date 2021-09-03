<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use \App\Http\Controllers\{HomeController,BookController,BookReportController,OrderController,UserSettingsController,UserChangePassword};
use \App\Http\Controllers\Admin\{AdminDashboardController,AdminBookController,AdminUsersController};

/**
 * -- My Notes --
 * 1- I installed  laravel/ui package to use Auth::routes();  instead of require __DIR__ . '/auth.php';
 * 2- Problem : when i used 'namespace'=>'Admin' or 'namespace'=>'\App\Http\Controllers\Admin' inside route group array
 *   It works only with routes witch calling class without function parm. like AdminDashboardController::class
 *   But when calling class ass array with function parm it don't work :( like [AdminBookController::class, 'index']
 *   So i resolved this by use all classes like above on this file .
 */

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

Route::group(['prefix'=>'book','as'=>'books.'],function(){

    Route::group(['middleware'=>'auth'],function(){
        Route::get('create', [BookController::class, 'create'])->name('create');
        Route::post('store', [BookController::class, 'store'])->name('store');
        Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
        Route::post('{book}/report', [BookReportController::class, 'store'])->name('report.store');
    });
    Route::get('{book:slug}', [BookController::class, 'show'])->name('show');

});


Route::group(['prefix'=>'user','as'=>'user.','middleware'=>'auth'],function(){

    Route::get('books', [BookController::class, 'index'])->name('books.list');
    Route::get('books/{book:slug}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('books/{book:slug}', [BookController::class, 'update'])->name('books.update');
    Route::delete('books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

    Route::get('settings', [UserSettingsController::class, 'index'])->name('settings');
    Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');

});


//'namespace'=>'Admin' working only with >> oute::get('/', AdminDashboardController::class)->name('index');
Route::group(['prefix'=>'admin','as'=>'admin.','middleware'=>'isAdmin'],function(){

    Route::get('/', AdminDashboardController::class)->name('index');

    Route::get('books', [AdminBookController::class, 'index'])->name('books.index');
    Route::get('books/create', [AdminBookController::class, 'create'])->name('books.create');
    Route::post('books', [AdminBookController::class, 'store'])->name('books.store');
    Route::get('books/{book}/edit', [AdminBookController::class, 'edit'])->name('books.edit');
    Route::put('books/{book}', [AdminBookController::class, 'update'])->name('books.update');
    Route::delete('books/{book}', [AdminBookController::class, 'destroy'])->name('books.destroy');
    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');

    Route::get('users', [AdminUsersController::class, 'index'])->name('users.index');
    Route::get('users/{user}/edit', [AdminUsersController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [AdminUsersController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [AdminUsersController::class, 'destroy'])->name('users.destroy');
});

// with laravel/ui package
Auth::routes(); 

#require __DIR__ . '/auth.php';
