<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\EmailVerificationService;
use Illuminate\Http\Response;

class EmailVerificationLinkController extends Controller
{
    public function __invoke(string $token, EmailVerificationService $verification): Response
    {
        return response()
            ->view('auth.verify-email-result', [
                'verified' => $verification->verify($token),
            ])
            ->header('Referrer-Policy', 'no-referrer')
            ->header('Cache-Control', 'no-store');
    }
}
