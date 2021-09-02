<?php

use Illuminate\Support\Facades\Route;


Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

Route::get('book/{book}', [\App\Http\Controllers\BookController::class, 'show'])->name('books.show');



Route::middleware('auth')->group(function () {
// authenticated 

    // book report
    Route::resource('books.report', \App\Http\Controllers\BookReportController::class)->only(['create','store']);

    Route::get('/books/create', [\App\Http\Controllers\BookController::class, 'create'])->name('books.create');
    Route::post('/books/store', [\App\Http\Controllers\BookController::class, 'store'])->name('books.store');


        Route::name('user.')->prefix('user')->group(function () {
        // prefix user
            Route::resource('books', \App\Http\Controllers\BookController::class)
                ->only(['index','edit','update','destroy'])
                ->names([
                    'index' => 'books.list'
                ]);

            Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
            
            Route::get('/settings', [\App\Http\Controllers\UserSettingsController::class, 'index'])->name('settings');
            Route::post('/settings/{user}', [\App\Http\Controllers\UserSettingsController::class, 'update'])->name('settings.update');
            Route::post('settings/password/change/{user}', [\App\Http\Controllers\UserChangePassword::class, 'update'])->name('password.update');
        
        });

        Route::name('admin.')->prefix('admin')->middleware('isAdmin')->group(function () {
        // prefix admin || isAdmin

            Route::get('/', \App\Http\Controllers\Admin\AdminDashboardController::class)->name('index');

            // admin book resource
            Route::put('/book/approve/{book}', [\App\Http\Controllers\Admin\AdminBookController::class, 'approveBook'])->name('books.approve');
            Route::resource('books', \App\Http\Controllers\Admin\AdminBookController::class);

            // admin user resource
            Route::resource('users', \App\Http\Controllers\Admin\AdminUsersController::class)
            ->only(['index','update','edit','destroy']);
        
        });

});

require __DIR__ . '/auth.php';
