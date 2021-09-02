<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('', BookController::class)->names([
        'create' => 'books.create',
        'store' => 'books.store',
    ]);

    Route::get('{book:slug}/report/create', [BookReportController::class, 'create'])->name('books.report.create');
    Route::post('{book}/report', [BookReportController::class, 'store'])->name('books.report.store');
});

Route::get('{book:slug}', [BookController::class, 'show'])->name('books.show');

require __DIR__ . '/auth.php';
