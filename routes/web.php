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

Route::middleware('auth')->group(function(){

        Route::resource('books', \App\Http\Controllers\BookController::class)->only(['create', 'store', 'show']);
       
        Route::get('book/{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->name('book.report.create');
        Route::post('book/{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->name('report.store');
    
    
    
    Route::group(['prefix' => 'user', 'as' => ''], function(){

        Route::resource('books', \App\Http\Controllers\BookController::class)->except(['create', 'store', 'show']);
        
        Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');

        Route::prefix('settings')->group(function(){
            Route::get('/', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('settings');
            Route::post('{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('settings.update');
            Route::post('password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('password.update');
        });
    });

});



Route::group(['middleware'=> 'isAdmin', 'prefix' => 'admin', 'as'=>'admin.'], function(){

    Route::get('/', \App\Http\Controllers\Admin\AdminDashboardController::class)->name('index');
    Route::put('book/approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->name('books.approve');

    Route::resource('books', \App\Http\Controllers\Admin\AdminBookController::class);
    Route::resource('users', \App\Http\Controllers\Admin\AdminUsersController::class);
});


require __DIR__ . '/auth.php';
