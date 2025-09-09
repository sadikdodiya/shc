<?php

use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\Auth\CustomPasswordResetLinkController;
use App\Http\Controllers\Auth\CustomNewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// Authentication Routes...
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('login', [CustomLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CustomLoginController::class, 'login']);

    // Password Reset Routes
    Route::get('forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('forgot-password', [CustomPasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', function ($token) {
        return view('auth.passwords.reset', ['request' => request()]);
    })->name('password.reset');

    Route::post('reset-password', [CustomNewPasswordController::class, 'store'])
        ->name('password.update');
});

// Logout Route
Route::post('logout', [CustomLoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Email Verification Routes...
Route::middleware(['auth', 'throttle:6,1'])->group(function () {
    Route::get('verify-email', function () {
        return request()->user()->hasVerifiedEmail()
            ? redirect()->intended(route('dashboard'))
            : view('auth.verify-email');
    })->name('verification.notice');

    Route::post('email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return $request->wantsJson()
                ? response()->json(['message' => 'Email already verified'], 200)
                : redirect()->intended(route('dashboard'));
        }

        $request->user()->sendEmailVerificationNotification();

        return $request->wantsJson()
            ? response()->json(['message' => 'Verification link sent'])
            : back()->with('status', 'verification-link-sent');
    })->name('verification.send');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed'])
        ->name('verification.verify');
});

// Confirm Password...
Route::middleware('auth')->group(function () {
    Route::get('confirm-password', function () {
        return view('auth.confirm-password');
    })->name('password.confirm');

    Route::post('confirm-password', function (Request $request) {
        if (! Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors([
                'password' => ['The provided password does not match our records.']
            ]);
        }

        $request->session()->passwordConfirmed();

        return redirect()->intended();
    })->name('password.confirm');
});
