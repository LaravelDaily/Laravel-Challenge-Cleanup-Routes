<?php

namespace App\Routes;

use App\Http\Controllers\Admin\AdminBookController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUsersController;
use Illuminate\Support\Facades\Route;

class AdminRoutes implements RouteGroup
{

    public static function register()
    {
        // ROUTE: admin/
        Route::group(["prefix" => "admin", "as" => "admin.", "middleware" => "isAdmin"], function () {
            Route::get('/', AdminDashboardController::class)->name('index');

            // ROUTE: admin/books
            Route::group(["prefix" => "books", "as" => "books."], function () {
                Route::get('/', [AdminBookController::class, 'index'])->name('index');
                Route::post('/', [AdminBookController::class, 'store'])->name('store');
                Route::get('create', [AdminBookController::class, 'create'])->name('create');

                // ROUTE: admin/books/{book}
                Route::group(['prefix' => "{book}", "as" => "books."], function () {
                    Route::put('/', [AdminBookController::class, 'update'])->name('update');
                    Route::delete('/', [AdminBookController::class, 'destroy'])->name('destroy');
                    Route::get('edit', [AdminBookController::class, 'edit'])->name('edit');
                    Route::put('approve', [AdminBookController::class, 'approveBook'])->name('approve');
                });
            });

            // ROUTE: admin/users
            Route::group(["prefix" => "users", "as" => "users."], function () {
                Route::get('/', [AdminUsersController::class, 'index'])->name('index');

                // ROUTE: admin/users/{user}
                Route::group(["prefix" => "{user}"], function () {
                    Route::put('/', [AdminUsersController::class, 'update'])->name('update');
                    Route::delete('/', [AdminUsersController::class, 'destroy'])->name('destroy');
                    Route::get('edit', [AdminUsersController::class, 'edit'])->name('edit');

                });
            });
        });
    }
}