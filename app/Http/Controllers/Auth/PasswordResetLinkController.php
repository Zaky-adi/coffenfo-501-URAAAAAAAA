<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class PasswordResetLinkController extends Controller
{
    public function __invoke(string $token): Response
    {
        return response()
            ->view('auth.reset-password', ['token' => $token])
            ->header('Referrer-Policy', 'no-referrer')
            ->header('Cache-Control', 'no-store');
    }
}
