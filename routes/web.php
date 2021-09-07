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

Route::group([
    'namespace' => 'App\\Http\\Controllers'
], function () {
    Route::get('/', 'HomeController')->name('home');
    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::group([
            'as' => 'books.',
            'prefix' => 'book'
        ], function () {
            Route::get('create', 'BookController@create')->name('create');
            Route::post('store', 'BookController@store')->name('store');
            Route::get('{book:slug}/report/create', 'BookReportController@create')->name('report.create');
            Route::post('{book}/report', 'BookReportController@store')->name('report.store');
            Route::get('{book:slug}', 'BookController@show')->name('show');
        });
        Route::group([
            'prefix' => 'user',
            'as' => 'user.'
        ], function () {

            Route::group([
                'prefix' => 'books',
                'as' => 'books.'
            ], function () {
                Route::get('', 'BookController@index')->name('list');
                Route::get('{book:slug}/edit', 'BookController@edit')->name('edit');
                Route::put('{book:slug}', 'BookController@update')->name('update');
                Route::delete('{book}', 'BookController@destroy')->name('destroy');
            });

            Route::get('orders', 'OrderController@index')->name('orders.index');
            Route::group([
                'prefix' => 'settings',
                'as' => 'settings.'
            ], function () {
                Route::get('', 'UserSettingsController@index')->name('index');
                Route::post('{user}', 'UserSettingsController@update')->name('update');
                Route::post('password/change/{user}', 'UserChangePassword@update')->name('update');
            });
        });

    });
    Route::group([
        'prefix' => 'admin',
        'as' => 'admin.',
        'middleware' => 'isAdmin',
        'namespace' => 'Admin'
    ], function () {
        Route::get('', 'AdminDashboardController')->name('index');
        Route::group([
            'prefix' => 'books',
            'as' => 'books.'
        ], function () {
            Route::get('', 'AdminBookController@index')->name('index');
            Route::get('create', 'AdminBookController@create')->name('create');
            Route::post('', 'AdminBookController@store')->name('store');
            Route::get('{book}/edit', 'AdminBookController@edit')->name('edit');
            Route::put('{book}', 'AdminBookController@update')->name('update');
            Route::delete('{book}', 'AdminBookController@destroy')->name('destroy');
            Route::put('approve/{book}', 'AdminBookController@approveBook')->name('approve');
        });

        Route::group([
            'prefix' => 'users',
            'as' => 'users.'
        ], function () {
            Route::get('', 'AdminUsersController@index')->name('index');
            Route::get('{user}/edit', 'AdminUsersController@edit')->name('edit');
            Route::put('{user}', 'AdminUsersController@update')->name('update');
            Route::delete('{user}', 'AdminUsersController@destroy')->name('destroy');
        });
    });
});


require __DIR__ . '/auth.php';
