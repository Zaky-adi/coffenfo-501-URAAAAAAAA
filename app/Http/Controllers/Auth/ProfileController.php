<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdatePreferencesRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $preferences = DB::table('user_preferences')->where('user_id', $user->id)->first();
        $points = DB::table('user_points')->where('user_id', $user->id)->value('balance') ?? 0;

        return $this->success([
            'public_id' => $user->public_id,
            'full_name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'avatar_url' => $user->photo,
            'email_verified_at' => $user->email_verified_at?->toISOString(),
            'points_balance' => (int) $points,
            'preferences' => [
                'bean_type_id' => $preferences?->bean_type_id,
                'roast_level_id' => $preferences?->roast_level_id,
            ],
        ]);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();
        $updates = [];
        if (array_key_exists('full_name', $data)) {
            $updates['name'] = $data['full_name'];
        }
        if (array_key_exists('username', $data)) {
            $updates['username'] = $data['username'];
        }
        if (array_key_exists('photo_upload_id', $data)) {
            $updates['photo'] = null;
            if ($data['photo_upload_id'] !== null) {
                $upload = DB::table('uploads')
                    ->where('public_id', $data['photo_upload_id'])
                    ->where('user_id', $user->id)
                    ->where('purpose', 'avatar')
                    ->where('status', 'ready')
                    ->whereNull('consumed_at')
                    ->first();
                if (!$upload) {
                    return $this->error('NOT_FOUND', 'Unggahan avatar tidak ditemukan atau tidak dapat digunakan.', 404);
                }
                $updates['photo'] = $upload->url;
                DB::table('uploads')->where('id', $upload->id)->update(['consumed_at' => now()]);
            }
        }

        if ($updates !== []) {
            $user->forceFill($updates)->save();
        }

        return $this->show($request);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        if (!Hash::check($request->string('current_password')->toString(), $user->password)) {
            return $this->error('VALIDATION_ERROR', 'Kata sandi saat ini tidak benar.', 422, [
                ['field' => 'current_password', 'message' => 'Kata sandi saat ini tidak benar.'],
            ]);
        }

        $newPassword = $request->string('new_password')->toString();
        if (strcasecmp($newPassword, (string) $user->email) === 0 || strcasecmp($newPassword, (string) $user->name) === 0) {
            return $this->error('VALIDATION_ERROR', 'Kata sandi tidak boleh sama dengan email atau nama.', 422, [
                ['field' => 'new_password', 'message' => 'Kata sandi tidak boleh sama dengan email atau nama.'],
            ]);
        }

        DB::transaction(function () use ($user, $newPassword, $request): void {
            $user->forceFill(['password' => Hash::make($newPassword), 'password_changed_at' => now()])->save();
            DB::table('refresh_tokens')->where('user_id', $user->id)->whereNull('revoked_at')->update(['revoked_at' => now()]);
            $user->tokens()->delete();
            DB::table('user_devices')->where('user_id', $user->id)->whereNotNull('push_token')
                ->update(['push_token' => null, 'push_enabled' => false, 'updated_at' => now()]);
            $this->audit($user, 'password.change', $request);
        });

        return $this->success(['message' => 'Kata sandi berhasil diubah. Silakan masuk kembali.']);
    }

    public function updatePreferences(UpdatePreferencesRequest $request): JsonResponse
    {
        $user = $request->user();
        DB::table('user_preferences')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'bean_type_id' => $request->input('bean_type_id'),
                'roast_level_id' => $request->input('roast_level_id'),
                'updated_at' => now(),
            ],
        );

        return $this->show($request);
    }

    public function destroy(DeleteAccountRequest $request): JsonResponse
    {
        $user = $request->user();
        if (!Hash::check($request->string('password')->toString(), $user->password)) {
            return $this->error('VALIDATION_ERROR', 'Kata sandi tidak benar.', 422, [
                ['field' => 'password', 'message' => 'Kata sandi tidak benar.'],
            ]);
        }

        DB::transaction(function () use ($user, $request): void {
            DB::table('refresh_tokens')->where('user_id', $user->id)->whereNull('revoked_at')->update(['revoked_at' => now()]);
            DB::table('user_devices')->where('user_id', $user->id)->whereNotNull('push_token')
                ->update(['push_token' => null, 'push_enabled' => false, 'updated_at' => now()]);
            $user->tokens()->delete();
            DB::table('user_tokens')->where('user_id', $user->id)->update(['used_at' => now()]);
            $this->audit($user, 'user.account_anonymized', $request);
            $user->forceFill([
                'name' => 'Pengguna dihapus',
                'email' => 'deleted+' . Str::uuid() . '@coffenfo.invalid',
                'username' => null,
                'phone' => null,
                'photo' => null,
                'latitude' => null,
                'longitude' => null,
                'password' => Hash::make(Str::random(64)),
                'status' => 'deleted',
                'deleted_at' => now(),
                'remember_token' => null,
            ])->save();
            DB::table('user_privacy_settings')->where('user_id', $user->id)->update([
                'personalization_enabled' => false,
                'location_enabled' => false,
                'consent_version' => null,
                'consented_at' => null,
            ]);
            foreach (['user_events', 'user_taste_weights', 'user_cafe_scores', 'user_locations'] as $table) {
                DB::table($table)->where('user_id', $user->id)->delete();
            }
        });

        return response()->json(['success' => true, 'data' => ['message' => 'Akun berhasil dihapus.'], 'request_id' => (string) Str::uuid()]);
    }

    private function audit(User $user, string $action, Request $request): void
    {
        DB::table('audit_logs')->insert([
            'actor_user_id' => $user->id,
            'actor_role' => $user->role,
            'action' => $action,
            'entity_type' => 'user',
            'entity_id' => (string) $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'metadata' => json_encode(['public_id' => $user->public_id], JSON_THROW_ON_ERROR),
            'created_at' => now(),
        ]);
    }

    private function success(array $data): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $data, 'request_id' => (string) Str::uuid()]);
    }

    private function error(string $code, string $message, int $status, array $details = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => ['code' => $code, 'message' => $message, 'details' => $details],
            'request_id' => (string) Str::uuid(),
        ], $status);
    }
}
