<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\BookReportController;
use App\Http\Controllers\UserSettingsController;

/**=================================//
//******** Auth Routes **********
 ** ================================ */
Route::middleware('auth')->group(function () {

	//==========*** User ***===========
	Route::prefix('user/')->name('user.')->group(function () {

		Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

		Route::prefix('books/')->name('books.')->group(function () {
			Route::get(   '',                          [BookController::class, 'index'              ])->name('list');
			Route::put(   '{book:slug}',               [BookController::class, 'update'             ])->name('update');
			Route::get(   '{book:slug}/edit',          [BookController::class, 'edit'               ])->name('edit');
			Route::delete('{book}',                    [BookController::class, 'destroy'            ])->name('destroy');
		});

		Route::prefix('settings/')->group(function () {
			Route::get( '',                            [UserSettingsController::class, 'index'      ])->name('settings');
			Route::post('{user}',                      [UserSettingsController::class, 'update'     ])->name('settings.update');
			Route::post('password/change/{user}',      [UserChangePassword::class, 'update'         ])->name('password.update');
		});

	});

	//==========*** Book ***===========
	Route::prefix('book/')->name('books.')->group(function () {
		Route::get( 'create',                       [BookController::class, 'create'            ])->name('create');
		Route::post('store',                        [BookController::class, 'store'             ])->name('store');
		Route::post('{book}/report',                [BookReportController::class, 'store'       ])->name('report.store');
		Route::get( '{book:slug}/report/create',    [BookReportController::class, 'create'      ])->name('report.create');
	});

});

/**=================================//
//******** Public Routes **********
 ** ================================ */
Route::view('/', 'front.home')->name('home');
Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

require __DIR__ . '/auth.php';
