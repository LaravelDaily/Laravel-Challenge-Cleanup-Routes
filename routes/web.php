<?php


use App\Http\Controllers\{
    BookController,
    HomeController,
    OrderController,
    UserSettingsController,
    UserChangePassword,
    BookReportController,
};

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

Route::get('/', HomeController::class)->name('home');

Route::group(['middleware'=>'auth'], function(){
    Route::resource('books', BookController::class)
        ->only(['create','store','show']);

    Route::resource('books.report',BookReportController::class)
        ->only(['create','store'])
        ->scoped(['book'=>'slug'])
        ->shallow();

    Route::group(['prefix' => 'user', 'as'=>'user.'],function(){
        Route::resource('books',BookController::class)
            ->only(['index','edit','update','destroy'])
            ->scoped(['book'=>'slug'])
            ->names(['index' => 'books.list'])
            ->shallow();
        Route::get('settings',[UserSettingsController::class, 'index'])->name('settings');
        Route::group(['prefix' => 'settings'],function (){
            Route::post('password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');

            Route::post('{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        });
        Route::resource('orders',OrderController::class)->only('index');
    });
});
Route::get('/books/{book:slug}',[BookController::class, 'show'])->name('books.show');

require __DIR__ . '/auth.php';
