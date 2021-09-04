<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');

Route::resource('book',BookController::class)
    ->scoped(['book' => 'slug'])
    ->only('show')
    ->names(['show' => 'books.show']);

