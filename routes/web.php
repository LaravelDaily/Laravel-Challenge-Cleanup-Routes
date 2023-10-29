<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
        HomeController,
        BookController,
        BookReportController,
        OrderController,
        UserChangePassword,
        UserSettingsController,
        Admin\AdminBookController,
        Admin\AdminUsersController,    
        Admin\AdminDashboardController 
};

Route::get('/',HomeController::class)->name('home');
Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::middleware(['auth'])->group(function () {
    // book routes
    Route::name('books')->group(function(){
        Route::get('book/create', [BookController::class, 'create'])
                ->name('create');
        Route::post('book/store', [BookController::class, 'store'])
                ->name('store');
        Route::get('book/{book:slug}/report/create', [BookReportController::class, 'create'])
                ->name('report.create');
        Route::post('book/{book}/report', [BookReportController::class, 'store'])
                ->name('report.store');    
    });
    // user-book routes
    Route::name('user.books')->group(function(){
        Route::get('user/books', [BookController::class, 'index'])
                ->name('list');
        Route::get('user/books/{book:slug}/edit', [BookController::class, 'edit'])
                ->name('edit');
        Route::put('user/books/{book:slug}', [BookController::class, 'update'])
                ->name('update');
        Route::delete('user/books/{book}', [BookController::class, 'destroy'])
                ->name('destroy');
    });   
    // user-order routes
    Route::get('user/orders', [OrderController::class, 'index'])
            ->name('user.orders.index');
    // user-settings routes
    Route::name('user.settings')->group(function(){
        Route::get('user/settings', [UserSettingsController::class, 'index']);
        Route::post('user/settings/{user}', [UserSettingsController::class, 'update'])
                ->name('update');
        Route::post('user/settings/password/change/{user}', [UserChangePassword::class, 'update'])
                ->name('update');
    });
    
});

Route::middleware(['isAdmin'])->group(function () {
    // admin-books routes
    Route::name('admin.books')->group(function(){
        Route::get('admin/books', [AdminBookController::class, 'index'])
                ->name('index');
        Route::get('admin/books/create', [AdminBookController::class, 'create'])
                ->name('create');
        Route::post('admin/books', [AdminBookController::class, 'store'])
                ->name('store');
        Route::get('admin/books/{book}/edit', [AdminBookController::class, 'edit'])
                ->name('edit');
        Route::put('admin/books/{book}', [AdminBookController::class, 'update'])
                ->name('update');
        Route::delete('admin/books/{book}', [AdminBookController::class, 'destroy'])
                ->name('destroy');
        Route::put('admin/book/approve/{book}', [AdminBookController::class, 'approveBook'])
                ->name('approve');
    });
    Route::get('admin', AdminDashboardController::class)
            ->name('admin.index');
    // admin-users routes
    Route::name('admin.users')->group(function(){
        Route::get('admin/users', [AdminUsersController::class, 'index'])
            ->name('index');
    Route::get('admin/users/{user}/edit', [AdminUsersController::class, 'edit'])
            ->name('edit');
    Route::put('admin/users/{user}', [AdminUsersController::class, 'update'])
            ->name('update');
    Route::delete('admin/users/{user}', [AdminUsersController::class, 'destroy'])
            ->name('destroy');
    });
});


require __DIR__ . '/auth.php';
