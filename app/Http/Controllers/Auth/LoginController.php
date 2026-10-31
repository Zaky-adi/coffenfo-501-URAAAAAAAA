<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    private static ?string $dummyPasswordHash = null;

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $requestId = (string) Str::uuid();
        $identifier = $request->string('identifier')->toString();
        $password = $request->string('password')->toString();
        $device = $request->input('device', []);
        $platformHeader = strtolower((string) $request->header('X-Client-Platform', 'web'));
        if (!in_array($platformHeader, ['web', 'android', 'ios'], true)) {
            return $this->errorResponse(
                'VALIDATION_ERROR',
                'Data tidak valid.',
                422,
                [['field' => 'X-Client-Platform', 'message' => 'Platform klien tidak didukung.']],
                $requestId,
            );
        }
        $platform = $platformHeader;

        if ($platform !== 'web' && !is_array($device)) {
            return $this->errorResponse(
                'VALIDATION_ERROR',
                'Data tidak valid.',
                422,
                [['field' => 'device', 'message' => 'Informasi perangkat wajib untuk aplikasi mobile.']],
                $requestId,
            );
        }

        if ($platform !== 'web'
            && (!isset($device['device_id'], $device['platform'], $device['app_version'])
                || $device['platform'] !== $platform)) {
            return $this->errorResponse(
                'VALIDATION_ERROR',
                'Data tidak valid.',
                422,
                [['field' => 'device', 'message' => 'device_id, platform, dan app_version harus sesuai dengan platform klien.']],
                $requestId,
            );
        }

        $ipAddress = (string) ($request->ip() ?? '0.0.0.0');
        $deviceId = is_array($device) ? ($device['device_id'] ?? null) : null;
        $ipRateKey = 'auth-login:ip:' . hash('sha256', $ipAddress);
        $deviceRateKey = $deviceId ? 'auth-login:device:' . hash('sha256', $deviceId) : null;

        if (RateLimiter::tooManyAttempts($ipRateKey, 60)
            || ($deviceRateKey !== null && RateLimiter::tooManyAttempts($deviceRateKey, 10))) {
            return $this->errorResponse('RATE_LIMITED', 'Terlalu banyak percobaan. Coba lagi nanti.', 429, [], $requestId);
        }
        RateLimiter::hit($ipRateKey, 60);
        if ($deviceRateKey !== null) {
            RateLimiter::hit($deviceRateKey, 60);
        }

        $identifierHash = hash('sha256', $identifier);
        $user = User::query()
            ->whereRaw('LOWER(email) = ?', [$identifier])
            ->orWhereRaw('LOWER(username) = ?', [$identifier])
            ->first();

        if (!$user) {
            self::$dummyPasswordHash ??= Hash::make(Str::random(64));
            Hash::check($password, self::$dummyPasswordHash);
            $this->recordAttempt($identifierHash, $ipAddress, false, $request->userAgent());

            return $this->invalidCredentials($requestId);
        }

        $result = DB::transaction(function () use (
            $user,
            $identifierHash,
            $ipAddress,
            $request,
            $password,
            $device,
            $deviceId,
            $platform,
        ): array {
            /** @var User|null $lockedUser */
            $lockedUser = User::query()->whereKey($user->getKey())->lockForUpdate()->first();
            if (!$lockedUser) {
                return ['status' => 'invalid'];
            }

            if ($lockedUser->locked_until && now()->lt($lockedUser->locked_until)) {
                $this->recordAttempt($identifierHash, $ipAddress, false, $request->userAgent());
                return ['status' => 'locked'];
            }

            if (!Hash::check($password, (string) $lockedUser->password)) {
                $maxAttempts = max(1, (int) (DB::table('app_settings')
                    ->where('setting_key', 'login_max_attempts')
                    ->value('setting_value') ?? 5));
                $lockoutMinutes = max(1, (int) (DB::table('app_settings')
                    ->where('setting_key', 'login_lockout_minutes')
                    ->value('setting_value') ?? 15));

                $lockedUser->failed_login_count = min(255, ((int) $lockedUser->failed_login_count) + 1);
                if ($lockedUser->failed_login_count >= $maxAttempts) {
                    $lockedUser->locked_until = now()->addMinutes($lockoutMinutes);
                }
                $lockedUser->save();
                $this->recordAttempt($identifierHash, $ipAddress, false, $request->userAgent());

                if ($lockedUser->locked_until !== null) {
                    DB::table('audit_logs')->insert([
                        'actor_user_id' => $lockedUser->id,
                        'actor_role' => $lockedUser->role,
                        'action' => 'auth.account_locked',
                        'entity_type' => 'user',
                        'entity_id' => (string) $lockedUser->id,
                        'ip_address' => $ipAddress,
                        'user_agent' => substr((string) $request->userAgent(), 0, 255),
                        'metadata' => json_encode(['reason' => 'failed_login_limit'], JSON_THROW_ON_ERROR),
                        'created_at' => now(),
                    ]);

                    return ['status' => 'locked_after_failure'];
                }

                return ['status' => 'invalid'];
            }

            if ($lockedUser->status !== 'active') {
                $this->recordAttempt($identifierHash, $ipAddress, false, $request->userAgent());
                return ['status' => 'invalid'];
            }

            $lockedUser->forceFill([
                'failed_login_count' => 0,
                'locked_until' => null,
                'last_login_at' => now(),
            ])->save();
            $this->recordAttempt($identifierHash, $ipAddress, true, $request->userAgent());

            if ($platform !== 'web' && $deviceId !== null) {
                $knownDevice = DB::table('user_devices')
                    ->where('user_id', $lockedUser->id)
                    ->where('device_id', $deviceId)
                    ->exists();

                if (!$knownDevice) {
                    DB::table('notifications')->insert([
                        'user_id' => $lockedUser->id,
                        'type' => 'auth.new_device',
                        'category' => 'system',
                        'title' => 'Login dari perangkat baru',
                        'body' => 'Akun Anda digunakan pada perangkat baru.',
                        'data' => json_encode([
                            'device_id' => $deviceId,
                            'platform' => $platform,
                        ], JSON_THROW_ON_ERROR),
                        'created_at' => now(),
                    ]);

                    DB::table('user_devices')->insert([
                        'user_id' => $lockedUser->id,
                        'device_id' => $deviceId,
                        'platform' => $platform,
                        'device_name' => is_array($device) ? ($device['device_name'] ?? null) : null,
                        'app_version' => is_array($device) ? ($device['app_version'] ?? null) : null,
                        'last_seen_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    DB::table('user_devices')
                        ->where('user_id', $lockedUser->id)
                        ->where('device_id', $deviceId)
                        ->update([
                            'platform' => $platform,
                            'device_name' => is_array($device) ? ($device['device_name'] ?? null) : null,
                            'app_version' => is_array($device) ? ($device['app_version'] ?? null) : null,
                            'last_seen_at' => now(),
                            'updated_at' => now(),
                        ]);
                }
            }

            $familyId = (string) Str::uuid();
            $accessToken = $lockedUser->createToken('auth:' . $familyId, ['*'], now()->addMinutes(15));
            $refreshToken = Str::random(64);

            DB::table('refresh_tokens')->insert([
                'user_id' => $lockedUser->id,
                'family_id' => $familyId,
                'client_platform' => $platform,
                'device_id' => $deviceId,
                'device_name' => is_array($device) ? ($device['device_name'] ?? null) : null,
                'app_version' => is_array($device) ? ($device['app_version'] ?? null) : null,
                'token_hash' => hash('sha256', $refreshToken),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
                'ip_address' => $ipAddress,
                'expires_at' => now()->addDays(14),
                'last_used_at' => now(),
            ]);

            return [
                'status' => 'success',
                'user' => $lockedUser,
                'access_token' => $accessToken->plainTextToken,
                'refresh_token' => $refreshToken,
            ];
        });

        if ($result['status'] === 'locked') {
            return $this->errorResponse('ACCOUNT_LOCKED', 'Akun dikunci sementara.', 423, [], $requestId);
        }

        if ($result['status'] !== 'success') {
            return $this->invalidCredentials($requestId);
        }

        $responseData = [
            'access_token' => $result['access_token'],
            'token_type' => 'Bearer',
            'expires_in' => 900,
            'user' => [
                'public_id' => $result['user']->public_id,
                'name' => $result['user']->name,
                'username' => $result['user']->username,
                'email' => $result['user']->email,
                'role' => $result['user']->role,
                'email_verified' => $result['user']->email_verified_at !== null,
            ],
        ];

        $response = response()->json([
            'success' => true,
            'data' => $responseData,
            'request_id' => $requestId,
        ]);

        if ($platform === 'web') {
            $response->withCookie(cookie(
                'refresh_token',
                $result['refresh_token'],
                14 * 24 * 60,
                '/api/v1/auth',
                null,
                true,
                true,
                false,
                'strict',
            ));
        } else {
            $responseData['refresh_token'] = $result['refresh_token'];
            $response->setData([
                'success' => true,
                'data' => $responseData,
                'request_id' => $requestId,
            ]);
        }

        return $response;
    }

    private function recordAttempt(string $identifierHash, string $ipAddress, bool $success, ?string $userAgent): void
    {
        DB::table('login_attempts')->insert([
            'identifier_hash' => $identifierHash,
            'ip_address' => $ipAddress,
            'success' => $success,
            'user_agent' => substr((string) $userAgent, 0, 255),
            'created_at' => now(),
        ]);
    }

    private function invalidCredentials(string $requestId): JsonResponse
    {
        return $this->errorResponse(
            'UNAUTHENTICATED',
            'Email/username atau kata sandi salah.',
            401,
            [],
            $requestId,
        );
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
