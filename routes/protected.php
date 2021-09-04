<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Protected Web Routes
|--------------------------------------------------------------------------
*/
Route::prefix('user')->name('user.')->group(function (){
    Route::resource('orders', OrderController::class,['only' => 'index']);

    Route::resource('books', BookController::class)
        ->scoped(['book' => 'slug'])
        ->only(['edit', 'update', 'destroy', 'index'])
        ->names(['index' => 'books.list']);

    Route::resource('settings', UserSettingsController::class)
        ->only(['index', 'update'])
        ->names(['index' => 'settings']);

    // Couldn't find a way to add those two 'update' methods to the resource since they are POST and not PUT methods
    Route::post('settings/{user}', [UserSettingsController::class, 'update']);
    Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])
        ->name('password.update');
});

Route::resource('book',BookController::class)
    ->only(['create', 'store'])
    ->names(['create' => 'books.create', 'store' => 'books.store']);

Route::resource('books.report', BookReportController::class)
    ->only(['create', 'store'])
    ->scoped(['book' => 'slug'])
    ->names(['create' => 'books.report.create', 'store' => 'books.report.store'])
    ->shallow();