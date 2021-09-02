<?php

namespace App\Routes;

use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserChangePassword;
use App\Http\Controllers\UserSettingsController;
use Illuminate\Support\Facades\Route;

class UserRoutes implements RouteGroup
{

    public static function register()
    {
        Route::group(["prefix" => "user", "as" => "user.", "middleware" => "auth"], function () {

            Route::group(["prefix" => "books", "as" => "books."], function () {
                Route::get('/', [BookController::class, 'index'])->name('list');
                Route::get('{book:slug}/edit', [BookController::class, 'edit'])->name('edit');
                Route::put('{book:slug}', [BookController::class, 'update'])->name('update');
                Route::delete('/{book}', [BookController::class, 'destroy'])->name('destroy');
            });

            Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

            Route::group(["prefix" => "settings"], function () {
                Route::get('/', [UserSettingsController::class, 'index'])->name('settings');
                Route::post('{user}', [UserSettingsController::class, 'update'])->name('settings.update');
                Route::post('password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
            });


        });
    }
}