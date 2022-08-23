<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{BookController, BookReportController, HomeController};

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

Route::prefix('book')->middleware('auth')->as('books.')->group(function () {
    Route::resource('/', BookController::class)->only(['create', 'store']);
    Route::resource('{book:slug}/report', BookReportController::class)->only(['create', 'store']);
});

Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

require __DIR__ . '/auth.php';
