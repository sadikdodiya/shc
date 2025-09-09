<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
            return response()->json(['message' => 'Invalid verification link'], 403);
        }

        if (! hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link'], 403);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return $request->wantsJson()
                        ? response()->json(['message' => 'Email already verified'], 200)
                        : redirect(config('auth.verification.redirect'));
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return $request->wantsJson()
                    ? response()->json(['message' => 'Email verified successfully'])
                    : redirect(config('auth.verification.redirect'))->with('verified', true);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $request->wantsJson()
                        ? response()->json(['message' => 'Email already verified'], 200)
                        : redirect(config('auth.verification.redirect'));
        }

        $request->user()->sendEmailVerificationNotification();

        return $request->wantsJson()
                    ? response()->json(['message' => 'Verification link sent'])
                    : back()->with('status', 'verification-link-sent');
    }
}
