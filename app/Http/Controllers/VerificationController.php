<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Require user to be authenticated before they can access these routes
        $this->middleware('auth');
        // Ensure user is unverified before they can access verification routes
        $this->middleware('signed')->only('verify');
        // Throttle requests to resend verification emails
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated user's email as verified.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect($this->redirectPath())->with('verified', true);
    }

    /**
     * Resend the email verification notification.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $user->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }
}
