<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment(['local', 'testing'])) {
            throw new RuntimeException('AdminUserSeeder hanya boleh dijalankan di environment local/testing.');
        }

        $user = User::query()->firstOrCreate(
            ['email' => 'admin@example.test'],
            [
                'public_id' => (string) Str::uuid(),
                'name' => 'Coffenfo Admin Dummy',
                'username' => 'admin_dummy',
                'password' => Hash::make('Admin123!'),
                'role' => User::ROLE_ADMIN,
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
