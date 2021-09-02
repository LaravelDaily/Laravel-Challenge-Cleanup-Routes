<?php

use Illuminate\Support\Facades\Route;

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

/*
 * Authentication routes.
 *
 * middleware: 'web'
 */

Route::middleware("guest")->group(function(){
    Route::get("/register", [RegisteredUserController::class, "create"])->name("register");

    Route::post("/register", [RegisteredUserController::class, "store"]);

    Route::get("/login", [AuthenticatedSessionController::class, "create"])->name("login");

    Route::post("/login", [AuthenticatedSessionController::class, "store"]);

    Route::name("password.")->group(function(){
        Route::get("/forgot-password", [PasswordResetLinkController::class, "create"])->name("request");

        Route::post("/forgot-password", [PasswordResetLinkController::class, "store"])->name("email");

        Route::get("/reset-password/{token}", [NewPasswordController::class, "create"])->name("reset");

        Route::post("/reset-password", [NewPasswordController::class, "store"])->name("update");
    });
});

Route::middleware("auth")->group(function(){
    Route::name("verification.")->group(function(){
        Route::get("/verify-email", EmailVerificationPromptController::class)->name("notice");

        Route::get("/verify-email/{id}/{hash}", VerifyEmailController::class)->middleware(["signed", "throttle:6,1"])->name("verify");

        Route::post("/email/verification-notification", [EmailVerificationNotificationController::class, "store"])->middleware("throttle:6,1")->name("send");
    });

    Route::get("/confirm-password", [ConfirmablePasswordController::class, "show"])->name("password.confirm");

    Route::post("/confirm-password", [ConfirmablePasswordController::class, "store"]);

    Route::post("/logout", [AuthenticatedSessionController::class, "destroy"])->name("logout");
});
