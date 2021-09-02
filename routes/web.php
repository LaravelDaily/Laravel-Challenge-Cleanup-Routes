<?php

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


Route::get('/', \App\Http\Controllers\HomeController::class)->name('home');

\App\Providers\RouteServiceProvider::registerRoutes([
    \App\Routes\BookRoutes::class,
    \App\Routes\UserRoutes::class,
    \App\Routes\AdminRoutes::class,
    \App\Routes\AuthRoutes::class,
]);



