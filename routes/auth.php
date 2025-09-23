<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\InviteCodeController;

Route::middleware('guest')->group(function () {
    // Rotas públicas removidas - registro não é público
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// Rotas de registro protegidas (apenas para usuários autenticados - verificação no controller)
Route::middleware('auth')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('validate-invite', [RegisteredUserController::class, 'validateInvite'])->name('validate.invite');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/invite-codes', [InviteCodeController::class, 'index'])
        ->name('invite-codes.index');
    Route::get('/invite-codes/create', [InviteCodeController::class, 'create'])
        ->name('invite-codes.create');
    Route::post('/invite-codes', [InviteCodeController::class, 'store'])
        ->name('invite-codes.store');
    Route::delete('/invite-codes/{inviteCode}', [InviteCodeController::class, 'destroy'])
        ->name('invite-codes.destroy');
    Route::patch('/invite-codes/{inviteCode}/deactivate', [InviteCodeController::class, 'deactivate'])
        ->name('invite-codes.deactivate');
    Route::patch('/invite-codes/{inviteCode}/activate', [InviteCodeController::class, 'activate'])
        ->name('invite-codes.activate');
});
