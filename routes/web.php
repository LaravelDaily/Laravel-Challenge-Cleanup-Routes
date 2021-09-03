<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\UserSettingsController;



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

Route::group(['prefix'=>'user', 'as' => 'user.'], function() { 

    Route::group(['middleware' => 'auth'], function() { 

        Route::get('book/{book:slug}/report/create', [BookReportController::class, 'create'])->name('books.report.create');
        Route::post('book/{book}/report', [BookReportController::class, 'store'])->name('books.report.store');
        
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

        // -------------- User settings -----------------------
        Route::group(['prefix'=>'settings', 'as' => 'settings.'], function() { 
            Route::get('/', [UserSettingsController::class, 'index'])->name('index');
            Route::post('/{user}', [UserSettingsController::class, 'update'])->name('update');
            Route::post('/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
        }); 
    });

    Route::resource('books',BookController::class)->names('books');
});


require __DIR__ . '/auth.php';
