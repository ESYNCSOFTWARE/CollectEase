<?php

declare(strict_types=1);

use App\Domains\User\Controllers\Auth\AuthenticatedSessionController;
use App\Domains\User\Controllers\Auth\ConfirmablePasswordController;
use App\Domains\User\Controllers\Auth\EmailVerificationNotificationController;
use App\Domains\User\Controllers\Auth\EmailVerificationPromptController;
use App\Domains\User\Controllers\Auth\NewPasswordController;
use App\Domains\User\Controllers\Auth\PasswordController;
use App\Domains\User\Controllers\Auth\PasswordResetLinkController;
use App\Domains\User\Controllers\Auth\RegisteredUserController;
use App\Domains\User\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {

    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    Route::get('verify-user-email', EmailVerificationPromptController::class)
        ->name('verification.user');
});

Route::middleware('auth')->group(function (): void {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('email-confirmation/{id}/{hash}', VerifyEmailController::class)
        ->name('email.confirmation');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
