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

Route::get('/', 'HomeController')->name('home');


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['isAdmin']], function () {

    Route::get('/', 'AdminDashboardController')->name('index');
    Route::put('book/approve/{book}', 'AdminBookController@approveBook')->name('books.approve');

    Route::resource('books', 'AdminBookController');
    Route::resource('users', 'AdminUsersController')->except(['create', 'store', 'show']);
   
});


Route::group(['middleware' => ['auth']] ,function () {

    Route::resource('book', 'BookController')->names([ 'create' => 'books.create', 'store' => 'books.store'])
    ->only(['create', 'store']);


    Route::resource('book.report', 'BookController')->names(['store' => 'books.report.store', 'create' => 'books.report.create'])
    ->only(['create', 'store'])->parameters([
    'book' => 'book:slug'
    ]);
});


Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth']], function () {

    Route::resource('books', 'BookController')->names([ 'index' => 'books.list'])
    ->except(['create', 'store', 'show'])->parameters([
        'books' => 'book:slug'
    ]);
    
     
    Route::get('orders', 'OrderController@index')->name('orders.index');
    Route::get('settings', 'UserSettingsController@index')->name('settings');
    Route::post('settings/{user}', 'UserSettingsController@update')->name('settings.update');
    Route::post('settings/password/change/{user}', 'UserChangePassword@update')->name('password.update');
});


Route::get('book/{book:slug}', 'BookController@show')->name('books.show');

require __DIR__ . '/auth.php';
