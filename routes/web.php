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

Route::group(['middleware' => 'auth','prefix' => 'book','as' => 'books.'],function(){

    Route::get('create', [\App\Http\Controllers\BookController::class, 'create'])->name('create');
    Route::get('{book:slug}', [\App\Http\Controllers\BookController::class, 'show'])->name('show')->withoutMiddleware('auth');
    Route::post('/', [\App\Http\Controllers\BookController::class, 'store'])->name('store');

    Route::get('{book:slug}/report/create', [\App\Http\Controllers\BookReportController::class, 'create'])->name('report.create');
    Route::post('{book}/report', [\App\Http\Controllers\BookReportController::class, 'store'])->name('report.store');

});


require __DIR__ . '/auth.php';
