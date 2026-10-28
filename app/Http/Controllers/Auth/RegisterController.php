<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Notifications\ExistingAccountNotification;
use App\Notifications\RegisterVerificationNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Handle a registration request.
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $requestId = (string) Str::uuid();
        $email = $request->string('email')->toString();

        $existingUser = User::query()->where('email', $email)->first();
        if ($existingUser) {
            if ($existingUser->email_verified_at === null) {
                $this->resendVerificationLink($existingUser, $request, $requestId);
            } else {
                $this->notifyExistingAccount($email, $requestId);
            }

            return $this->acceptedResponse($requestId);
        }

        try {
            [$user, $rawToken] = DB::transaction(function () use ($request) {
                $personalizationEnabled = $request->boolean('personalization_enabled');
                $user = new User();
                $user->forceFill([
                    'public_id' => (string) Str::uuid(),
                    'name' => $request->input('full_name'),
                    'email' => $request->input('email'),
                    'username' => $request->input('username'),
                    'password' => Hash::make($request->input('password')),
                    'role' => User::ROLE_USER,
                    'status' => 'active',
                ]);
                $user->save();

                DB::table('user_preferences')->insert([
                    'user_id' => $user->id,
                    'bean_type_id' => $request->input('bean_type_id'),
                    'roast_level_id' => $request->input('roast_level_id'),
                ]);
                DB::table('user_points')->insert([
                    'user_id' => $user->id,
                    'balance' => 0,
                ]);
                DB::table('user_privacy_settings')->insert([
                    'user_id' => $user->id,
                    'personalization_enabled' => $personalizationEnabled,
                    'location_enabled' => false,
                    'consent_version' => $personalizationEnabled ? config('app.consent_version', 'v1') : null,
                    'consented_at' => $personalizationEnabled ? now() : null,
                ]);

                $rawToken = Str::random(60);
                DB::table('user_tokens')->insert([
                    'user_id' => $user->id,
                    'type' => 'email_verify',
                    'token_hash' => hash('sha256', $rawToken),
                    'expires_at' => now()->addHours(24),
                    'ip_address' => $request->ip(),
                ]);

                return [$user, $rawToken];
            });
        } catch (QueryException $exception) {
            // A concurrent registration can win the unique-email race after the pre-check.
            if (User::query()->where('email', $email)->exists()) {
                $this->notifyExistingAccount($email, $requestId);

                return $this->acceptedResponse($requestId);
            }

            throw $exception;
        }

        try {
            $user->notify(new RegisterVerificationNotification($rawToken));
        } catch (\Throwable $exception) {
            Log::warning('Registration verification email could not be delivered.', [
                'request_id' => $requestId,
                'exception' => $exception::class,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Jika pendaftaran dapat diproses, instruksi selanjutnya akan dikirim melalui email.',
            ],
            'request_id' => $requestId,
        ], 202);
    }

    private function notifyExistingAccount(string $email, string $requestId): void
    {
        try {
            Notification::route('mail', $email)->notify(new ExistingAccountNotification());
        } catch (\Throwable $exception) {
            Log::warning('Existing-account registration notice could not be delivered.', [
                'request_id' => $requestId,
                'exception' => $exception::class,
            ]);
        }
    }

    private function resendVerificationLink(User $user, Request $request, string $requestId): void
    {
        $rateLimitKey = 'register-verification:' . hash('sha256', strtolower($user->email));
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            return;
        }
        RateLimiter::hit($rateLimitKey, 3600);

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
            Log::warning('Registration verification email could not be delivered.', [
                'request_id' => $requestId,
                'exception' => $exception::class,
            ]);
        }
    }

    private function acceptedResponse(string $requestId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Jika pendaftaran dapat diproses, instruksi selanjutnya akan dikirim melalui email.',
            ],
            'request_id' => $requestId,
        ], 202);
    }
}

