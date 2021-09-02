<?php

namespace App\Routes;

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReportController;
use Illuminate\Support\Facades\Route;

class BookRoutes implements RouteGroup
{
    public static function register()
    {
        Route::group(["prefix"=>"book","as"=>"books."],function(){
            Route::get('create', [BookController::class, 'create'])
                ->middleware('auth')
                ->name('create');

            Route::post('store', [BookController::class, 'store'])
                ->middleware('auth')
                ->name('store');

            Route::group(["prefix"=>"{book:slug}"],function(){
                Route::get('report/create', [BookReportController::class, 'create'])
                    ->middleware('auth')
                    ->name('report.create');

                Route::get('/', [BookController::class, 'show'])
                    ->name('books.show');
            });

            Route::post('{book}/report', [BookReportController::class, 'store'])
                ->middleware('auth')
                ->name('report.store');

        });
    }
}