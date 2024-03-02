<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController, BookController, BookReportController, OrderController, UserSettingsController, UserChangePassword
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


Route::middleware('auth')->group(function(){
    
    // scoped book:slug This allows you to retrieve a book by its slug directly in your controller method without needing to manually fetch it from the database and instead of the primary key
    Route::resource('book', BookController::class)->only(['create','store'])->names(['create' => 'books.create', 'store' => 'books.store'])->scoped(['book' => 'slug']);

    Route::prefix('user')->name('user.')->group(function(){
        Route::resource('books', BookController::class)->only(['index','edit','update','destroy'])->scoped(['book' => 'slug'])->names(['index' => 'books.list']);
   
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        
        Route::get('settings', [UserSettingsController::class, 'index'])->name('settings');
        Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
    
    });

    Route::prefix('book')->name('books.')->group(function(){
        Route::get('{book:slug}', [BookController::class, 'show'])->name('show')->withoutMiddleware('auth');
        Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');
        Route::post('{book}/report', [BookReportController::class, 'store'])->name('report.store');
    });
  });
  
require __DIR__ . '/auth.php';
