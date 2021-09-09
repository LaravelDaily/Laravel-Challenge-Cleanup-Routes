<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\
{
  HomeController,
  BookController,
  OrderController,
  BookReportController,
  UserSettingsController,
  UserChangePassword
};

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

//Auth Middleware
Route::middleware(['auth'])->group(function () {

//User Route prefix and name
Route::group(['prefix' => 'user','as'=>'user.'], function() {

 Route::resource('books',BookController::class)->except(['create','store','show'])
 ->names(['index'=>'books.list','edit'=>'books.edit','update'=>'books.update','destroy'=>'books.destroy']); 

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

Route::get('/settings', [UserSettingsController::class, 'index'])->name('settings');

Route::post('/settings/{user}', [UserSettingsController::class, 'update'])->name('settings.update');

Route::post('/settings/password/change/{user}', [UserChangePassword::class, 'update'])
->name('password.update');

});

//Book Route prefix and name
Route::group([ 'prefix' => 'book','as'=>'books.'], function() {

Route::resource('book',BookController::class)->only(['create','store'])->names(['create'=>'
  books,create','store'=>'books.store']);  


Route::get('/create', [BookController::class, 'create'])->name('create');

Route::post('/store', [BookController::class, 'store'])->name('store');

Route::get('/{book:slug}/report/create', [BookReportController::class, 'create'])->name('report.create');

Route::post('/{book}/report', [BookReportController::class, 'store'])->name('report.store');

});
});

Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

require __DIR__ . '/auth.php';
