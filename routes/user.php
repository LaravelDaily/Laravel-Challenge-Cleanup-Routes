<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{BookController, OrderController, UserChangePassword, UserSettingsController};

Route::prefix('user')->as('user.')->group(function () {
    Route::resource('books', BookController::class)->except(['show', 'create', 'store'])->scoped(['book' => 'slug'])->names(['index' => 'books.list']);
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('settings', [UserSettingsController::class, 'index'])->name('settings');
    Route::post('settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
});
