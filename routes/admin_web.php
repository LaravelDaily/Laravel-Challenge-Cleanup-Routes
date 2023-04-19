<?php


use \App\Http\Controllers\Admin\{
    AdminDashboardController,
    AdminBookController,
    AdminUsersController
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
Route::group(['middleware' => 'isAdmin', 'prefix' => 'admin', 'as'=>'admin.'], function (){
    Route::get('/', AdminDashboardController::class)->name('index');

    Route::put('book/approve/{book}', [AdminBookController::class, 'approveBook'])->name('books.approve');

    Route::resource('books', AdminBookController::class)->except('show');
    Route::resource('users',AdminUsersController::class)->only('index','edit','update','destroy');

});


require __DIR__ . '/auth.php';
