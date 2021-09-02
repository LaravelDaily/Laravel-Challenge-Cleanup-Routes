<?php

Route::resource('books',\App\Http\Controllers\BookController::class)
    ->parameters(['books' => 'book:slug'])
    ->names(['index' => 'books.list'])
    ->only(['index','edit','update','destroy']);

Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');

Route::group(['prefix' => 'settings'],function(){
    Route::get('/', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('settings');
    Route::post('{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('settings.update');
    Route::post('password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('password.update');
});