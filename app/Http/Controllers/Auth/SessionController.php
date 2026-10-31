<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RefreshTokenRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class SessionController extends Controller
{
    public function refresh(RefreshTokenRequest $request): JsonResponse
    {
        $requestId = (string) Str::uuid();
        $platform = strtolower((string) $request->header('X-Client-Platform', 'web'));
        if (!in_array($platform, ['web', 'android', 'ios'], true)) {
            return $this->errorResponse('VALIDATION_ERROR', 'Platform klien tidak didukung.', 422, $requestId);
        }

        $rawToken = $platform === 'web'
            ? (string) $request->cookie('refresh_token', '')
            : $request->string('refresh_token')->toString();
        if ($rawToken === '') {
            return $this->invalidRefreshToken($requestId);
        }

        $tokenHash = hash('sha256', $rawToken);
        $result = DB::transaction(function () use ($request, $platform, $tokenHash): array {
            $refreshToken = DB::table('refresh_tokens')
                ->where('token_hash', $tokenHash)
                ->lockForUpdate()
                ->first();

            if (!$refreshToken) {
                return ['status' => 'invalid'];
            }

            $user = User::query()->whereKey($refreshToken->user_id)->lockForUpdate()->first();
            if (!$user) {
                DB::table('refresh_tokens')
                    ->where('family_id', $refreshToken->family_id)
                    ->whereNull('revoked_at')
                    ->update(['revoked_at' => now()]);
                DB::table('personal_access_tokens')
                    ->where('tokenable_type', (new User())->getMorphClass())
                    ->where('tokenable_id', $refreshToken->user_id)
                    ->where('name', 'auth:' . $refreshToken->family_id)
                    ->delete();

                return ['status' => 'invalid'];
            }

            if ($user->status !== 'active') {
                $this->revokeFamily($user, (string) $refreshToken->family_id);

                return ['status' => 'invalid'];
            }
            if ($refreshToken->client_platform !== $platform) {
                return ['status' => 'invalid'];
            }

            if ($refreshToken->revoked_at !== null) {
                if ($refreshToken->replaced_by_id === null) {
                    return ['status' => 'invalid'];
                }

                $graceSeconds = max(0, (int) (DB::table('app_settings')
                    ->where('setting_key', 'refresh_grace_seconds')
                    ->value('setting_value') ?? 20));
                $revokedAt = strtotime((string) $refreshToken->revoked_at) ?: 0;
                $ageInSeconds = max(0, now()->timestamp - $revokedAt);

                if ($ageInSeconds > $graceSeconds) {
                    $this->revokeFamily($user, (string) $refreshToken->family_id);
                    DB::table('audit_logs')->insert([
                        'actor_user_id' => $user->id,
                        'actor_role' => $user->role,
                        'action' => 'auth.refresh_token_reuse',
                        'entity_type' => 'refresh_token',
                        'entity_id' => (string) $refreshToken->id,
                        'ip_address' => $request->ip(),
                        'user_agent' => substr((string) $request->userAgent(), 0, 255),
                        'metadata' => json_encode(['family_id' => $refreshToken->family_id], JSON_THROW_ON_ERROR),
                        'created_at' => now(),
                    ]);

                    return ['status' => 'reuse_detected'];
                }

                return $this->issueReplacement($user, $refreshToken, $request, $platform, false);
            }

            if (now()->greaterThanOrEqualTo($refreshToken->expires_at)) {
                return ['status' => 'invalid'];
            }

            return $this->issueReplacement($user, $refreshToken, $request, $platform, true);
        });

        if (!in_array($result['status'], ['success'], true)) {
            return $this->invalidRefreshToken($requestId);
        }

        $data = [
            'access_token' => $result['access_token'],
            'token_type' => 'Bearer',
            'expires_in' => 900,
        ];
        if ($platform !== 'web') {
            $data['refresh_token'] = $result['refresh_token'];
        }

        $response = response()->json([
            'success' => true,
            'data' => $data,
            'request_id' => $requestId,
        ]);

        if ($platform === 'web') {
            $response->withCookie($this->refreshCookie($result['refresh_token']));
        }

        return $response;
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $accessToken = $user->currentAccessToken();
        $familyId = $this->familyIdFromAccessToken($accessToken);
        $rawRefreshToken = (string) $request->cookie('refresh_token', '');

        DB::transaction(function () use ($user, $familyId, $rawRefreshToken, $accessToken): void {
            if ($familyId === null && $rawRefreshToken !== '') {
                $session = DB::table('refresh_tokens')
                    ->where('user_id', $user->id)
                    ->where('token_hash', hash('sha256', $rawRefreshToken))
                    ->lockForUpdate()
                    ->first();
                $familyId = $session?->family_id;
            }

            if ($familyId !== null) {
                $session = DB::table('refresh_tokens')
                    ->where('user_id', $user->id)
                    ->where('family_id', $familyId)
                    ->first();
                DB::table('refresh_tokens')
                    ->where('user_id', $user->id)
                    ->where('family_id', $familyId)
                    ->whereNull('revoked_at')
                    ->update(['revoked_at' => now()]);
                $user->tokens()->where('name', 'auth:' . $familyId)->delete();

                if ($session?->device_id) {
                    DB::table('user_devices')
                        ->where('user_id', $user->id)
                        ->where('device_id', $session->device_id)
                        ->update(['push_token' => null, 'push_enabled' => false, 'updated_at' => now()]);
                }
            }

            if ($accessToken instanceof PersonalAccessToken) {
                $accessToken->delete();
            }
        });

        $response = $this->successResponse('Sesi berhasil dikeluarkan.');
        return $response->withCookie(cookie()->forget('refresh_token', '/api/v1/auth'));
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $user = $request->user();

        DB::transaction(function () use ($user): void {
            DB::table('refresh_tokens')
                ->where('user_id', $user->id)
                ->whereNull('revoked_at')
                ->update(['revoked_at' => now()]);
            $user->tokens()->delete();
            DB::table('user_devices')
                ->where('user_id', $user->id)
                ->whereNotNull('push_token')
                ->update(['push_token' => null, 'push_enabled' => false, 'updated_at' => now()]);
        });

        $response = $this->successResponse('Semua sesi berhasil dikeluarkan.');
        return $response->withCookie(cookie()->forget('refresh_token', '/api/v1/auth'));
    }

    private function issueReplacement(
        User $user,
        object $oldToken,
        Request $request,
        string $platform,
        bool $rotateOldToken,
    ): array {
        $newRefreshToken = Str::random(64);
        $newRefreshTokenId = DB::table('refresh_tokens')->insertGetId([
            'user_id' => $user->id,
            'family_id' => $oldToken->family_id,
            'client_platform' => $platform,
            'device_id' => $oldToken->device_id,
            'device_name' => $oldToken->device_name,
            'app_version' => $oldToken->app_version,
            'token_hash' => hash('sha256', $newRefreshToken),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'ip_address' => $request->ip(),
            'expires_at' => now()->addDays(14),
            'last_used_at' => now(),
        ]);

        if ($rotateOldToken) {
            DB::table('refresh_tokens')->where('id', $oldToken->id)->update([
                'revoked_at' => now(),
                'replaced_by_id' => $newRefreshTokenId,
            ]);
        }

        $accessToken = $user->createToken(
            'auth:' . $oldToken->family_id,
            ['*'],
            now()->addMinutes(15),
        );

        return [
            'status' => 'success',
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $newRefreshToken,
        ];
    }

    private function revokeFamily(User $user, string $familyId): void
    {
        $session = DB::table('refresh_tokens')
            ->where('user_id', $user->id)
            ->where('family_id', $familyId)
            ->first();
        DB::table('refresh_tokens')
            ->where('user_id', $user->id)
            ->where('family_id', $familyId)
            ->whereNull('revoked_at')
            ->update(['revoked_at' => now()]);
        $user->tokens()->where('name', 'auth:' . $familyId)->delete();
        if ($session?->device_id) {
            DB::table('user_devices')
                ->where('user_id', $user->id)
                ->where('device_id', $session->device_id)
                ->update(['push_token' => null, 'push_enabled' => false, 'updated_at' => now()]);
        }
    }

    private function familyIdFromAccessToken(mixed $accessToken): ?string
    {
        if (!$accessToken instanceof PersonalAccessToken || !str_starts_with($accessToken->name, 'auth:')) {
            return null;
        }

        return substr($accessToken->name, strlen('auth:'));
    }

    private function refreshCookie(string $token)
    {
        return cookie(
            'refresh_token',
            $token,
            14 * 24 * 60,
            '/api/v1/auth',
            null,
            true,
            true,
            false,
            'strict',
        );
    }

    private function successResponse(string $message): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => ['message' => $message],
            'request_id' => (string) Str::uuid(),
        ]);
    }

    private function invalidRefreshToken(string $requestId): JsonResponse
    {
        return $this->errorResponse('UNAUTHENTICATED', 'Refresh token tidak valid atau kedaluwarsa.', 401, $requestId);
    }

    private function errorResponse(string $code, string $message, int $status, string $requestId): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $message,
                'details' => [],
            ],
            'request_id' => $requestId,
        ], $status);
    }
}
