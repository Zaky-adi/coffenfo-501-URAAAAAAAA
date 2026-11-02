<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class CafeOwnerUserSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment(['local', 'testing'])) {
            throw new RuntimeException('CafeOwnerUserSeeder hanya boleh dijalankan di environment local/testing.');
        }

        $user = User::query()->firstOrCreate(
            ['email' => 'owner@example.test'],
            [
                'public_id' => (string) Str::uuid(),
                'name' => 'Coffenfo Cafe Owner Dummy',
                'username' => 'owner_dummy',
                'password' => Hash::make('Owner123!'),
                'role' => User::ROLE_OWNER,
                'status' => 'active',
                'email_verified_at' => now(),
            ],
        );

        if ($user->wasRecentlyCreated) {
            DB::table('user_preferences')->insertOrIgnore(['user_id' => $user->id]);
            DB::table('user_points')->insertOrIgnore(['user_id' => $user->id, 'balance' => 0]);
            DB::table('user_privacy_settings')->insertOrIgnore(['user_id' => $user->id]);
        }
    }
}
