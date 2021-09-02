<?php

use App\Http\Controllers\Auth\{
    AuthenticatedSessionController,
    ConfirmablePasswordController,
    EmailVerificationNotificationController,
    EmailVerificationPromptController,
    NewPasswordController,
    PasswordResetLinkController,
    RegisteredUserController,
    VerifyEmailController
};
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::prefix('register')->group(function () {
        Route::get('', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('', [RegisteredUserController::class, 'store']);
    });

    Route::prefix('login')->group(function () {
        Route::get('', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('', [AuthenticatedSessionController::class, 'store']);
    });

    Route::name('password.')->group(function () {
        Route::prefix('forgot-password')->group(function () {
            Route::get('', [PasswordResetLinkController::class, 'create'])->name('request');
            Route::post('', [PasswordResetLinkController::class, 'store'])->name('email');
        });

        Route::prefix('reset-password')->group(function () {
            Route::post('', [NewPasswordController::class, 'store'])->name('update');
            Route::get('{token}', [NewPasswordController::class, 'create'])->name('reset');
        });
    });
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::prefix('confirm-password')->group(function () {
        Route::get('', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
        Route::post('', [ConfirmablePasswordController::class, 'store']);
    });

    Route::name('verification.')->group(function () {
        Route::middleware('throttle:6,1')->group(function () {
            Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->name('send');

            Route::prefix('verify-email')->group(function () {
                Route::get('', [EmailVerificationPromptController::class, '__invoke'])
                    ->withoutMiddleware('throttle:6,1')
                    ->name('notice');
                Route::get('{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                    ->middleware('signed')
                    ->name('verify');
            });
        });
    });
});
