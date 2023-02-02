<?php



use App\Http\Controllers\{
    BookController,
    BookReportController,
    HomeController,
    OrderController,
    UserChangePassword,
    UserSettingsController,
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

Route::get('book/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::middleware(['auth'])->group(function () {
    Route::resource('books', BookController::class)
        ->only(['create', 'store'])
        ->scoped(['book' => 'slug']);

    Route::resource('books.report', BookReportController::class)
        ->only(['create', 'store'])
        ->scoped(['book' => 'slug']);;

    Route::prefix('user')->name('user.')->group(function () {
        Route::resource('books', BookController::class)
            ->except(['create', 'store', 'show'])
            ->names(['index' => 'books.list'])
            ->scoped(['book' => 'slug']);

        Route::resource('orders', OrderController::class)->only('index');

        Route::prefix('settings')->group(function () {
            Route::post('password/change/{user}', [UserChangePassword::class, 'update'])->name('password.update');
            Route::post('{user}', [UserSettingsController::class, 'update'])->name('settings.update');
        });

        Route::resource('settings', UserSettingsController::class)
            ->names(['index' => 'settings'])
            ->only('index');
    });
});

// or define that in app/Providers/RouteServiceProvider
/*Route::middleware(['isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    require __DIR__ . '/admin.php';
});*/
require __DIR__ . '/auth.php';
