<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Models\User;
use App\Notifications\PasswordChangedNotification;
use App\Notifications\PasswordResetLinkNotification;
use App\Services\Auth\EmailVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AccountRecoveryController extends Controller
{
    public function resendVerification(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $requestId = (string) Str::uuid();
        $rateLimitKey = 'resend-verification:user:' . $user->getAuthIdentifier();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            return $this->successResponse('Jika email akun belum terverifikasi, tautan verifikasi akan dikirim.', $requestId);
        }
        RateLimiter::hit($rateLimitKey, 3600);

        if ($user->email_verified_at === null) {
            $rawToken = Str::random(60);
            DB::transaction(function () use ($user, $rawToken, $request) {
                DB::table('user_tokens')
                    ->where('user_id', $user->id)
                    ->where('type', 'email_verify')
                    ->whereNull('used_at')
                    ->update(['used_at' => now()]);

                DB::table('user_tokens')->insert([
                    'user_id' => $user->id,
                    'type' => 'email_verify',
                    'token_hash' => hash('sha256', $rawToken),
                    'expires_at' => now()->addHours(24),
                    'ip_address' => $request->ip(),
                ]);
            });

            try {
                $user->notify(new RegisterVerificationNotification($rawToken));
            } catch (\Throwable $exception) {
                Log::warning('Resend verification email could not be delivered.', [
                    'request_id' => $requestId,
                    'exception' => $exception::class,
                ]);
            }
        }

        return $this->successResponse('Jika email akun belum terverifikasi, tautan verifikasi akan dikirim.', $requestId);
    }

    public function verifyEmail(VerifyEmailRequest $request, EmailVerificationService $verification): JsonResponse
    {
        $verified = $verification->verify($request->string('token')->toString());

        if (!$verified) {
            return $this->errorResponse('VALIDATION_ERROR', 'Token verifikasi tidak valid atau sudah kedaluwarsa.', 422);
        }

        return $this->successResponse('Email berhasil diverifikasi.');
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $requestId = (string) Str::uuid();
        $email = $request->string('email')->toString();
        $rateLimitKey = 'forgot-password:' . hash('sha256', $email);

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            return $this->successResponse(
                'Jika email terdaftar, instruksi reset kata sandi akan dikirim.',
                $requestId,
            );
        }
        RateLimiter::hit($rateLimitKey, 3600);

        $user = User::query()->where('email', $email)->first();
        if ($user) {
            $rawToken = Str::random(64);
            DB::transaction(function () use ($user, $rawToken, $request) {
                DB::table('user_tokens')
                    ->where('user_id', $user->id)
                    ->where('type', 'password_reset')
                    ->whereNull('used_at')
                    ->update(['used_at' => now()]);

                DB::table('user_tokens')->insert([
                    'user_id' => $user->id,
                    'type' => 'password_reset',
                    'token_hash' => hash('sha256', $rawToken),
                    'expires_at' => now()->addMinutes(30),
                    'ip_address' => $request->ip(),
                ]);
            });

            try {
                $user->notify(new PasswordResetLinkNotification($rawToken));
            } catch (\Throwable $exception) {
                Log::warning('Password reset email could not be delivered.', [
                    'request_id' => $requestId,
                    'exception' => $exception::class,
                ]);
            }
        }

        return $this->successResponse(
            'Jika email terdaftar, instruksi reset kata sandi akan dikirim.',
            $requestId,
        );
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $requestId = (string) Str::uuid();
        $tokenHash = hash('sha256', $request->string('token')->toString());
        $newPassword = $request->string('new_password')->toString();

        $result = DB::transaction(function () use ($tokenHash, $newPassword, $request) {
            $token = DB::table('user_tokens')
                ->where('type', 'password_reset')
                ->where('token_hash', $tokenHash)
                ->lockForUpdate()
                ->first();

            if (!$token || $token->used_at !== null || now()->greaterThanOrEqualTo($token->expires_at)) {
                return ['status' => 'invalid'];
            }

            $user = User::query()->whereKey($token->user_id)->lockForUpdate()->first();
            if (!$user) {
                return ['status' => 'invalid'];
            }

            if (strcasecmp($newPassword, (string) $user->email) === 0
                || strcasecmp($newPassword, (string) $user->name) === 0) {
                return ['status' => 'password_conflict'];
            }

            DB::table('user_tokens')->where('id', $token->id)->update(['used_at' => now()]);
            $user->forceFill(['password' => Hash::make($newPassword)])->save();
            DB::table('refresh_tokens')
                ->where('user_id', $user->id)
                ->whereNull('revoked_at')
                ->update(['revoked_at' => now()]);
            DB::table('audit_logs')->insert([
                'actor_user_id' => $user->id,
                'actor_role' => $user->role,
                'action' => 'password.reset',
                'entity_type' => 'user',
                'entity_id' => (string) $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
                'metadata' => json_encode(['method' => 'email_token'], JSON_THROW_ON_ERROR),
                'created_at' => now(),
            ]);

            return ['status' => 'reset', 'user' => $user];
        });

        if ($result['status'] === 'password_conflict') {
            return $this->errorResponse('VALIDATION_ERROR', 'Kata sandi tidak boleh sama dengan email atau nama.', 422, [
                ['field' => 'new_password', 'message' => 'Kata sandi tidak boleh sama dengan email atau nama.'],
            ], $requestId);
        }

        if ($result['status'] !== 'reset') {
            return $this->errorResponse('VALIDATION_ERROR', 'Token reset tidak valid atau sudah kedaluwarsa.', 422, [], $requestId);
        }

        try {
            $result['user']->notify(new PasswordChangedNotification());
        } catch (\Throwable $exception) {
            Log::warning('Password change notice could not be delivered.', [
                'request_id' => $requestId,
                'exception' => $exception::class,
            ]);
        }

        return $this->successResponse('Kata sandi berhasil diubah.', $requestId);
    }

    private function successResponse(string $message, ?string $requestId = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => ['message' => $message],
            'request_id' => $requestId ?? (string) Str::uuid(),
        ]);
    }

    private function errorResponse(
        string $code,
        string $message,
        int $status,
        array $details = [],
        ?string $requestId = null,
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $message,
                'details' => $details,
            ],
            'request_id' => $requestId ?? (string) Str::uuid(),
        ], $status);
    }
}
