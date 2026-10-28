<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmailVerificationService
{
    public function verify(string $rawToken): bool
    {
        $tokenHash = hash('sha256', $rawToken);

        return DB::transaction(function () use ($tokenHash): bool {
            $token = DB::table('user_tokens')
                ->where('type', 'email_verify')
                ->where('token_hash', $tokenHash)
                ->lockForUpdate()
                ->first();

            if (!$token || $token->used_at !== null || now()->greaterThanOrEqualTo($token->expires_at)) {
                return false;
            }

            $user = User::query()->whereKey($token->user_id)->lockForUpdate()->first();
            if (!$user) {
                return false;
            }

            DB::table('user_tokens')->where('id', $token->id)->update(['used_at' => now()]);
            if ($user->email_verified_at === null) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            return true;
        });
    }
}
