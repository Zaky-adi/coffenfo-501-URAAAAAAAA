<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    /**
     * Handle a registration request.
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = DB::transaction(function () use ($request) {
            // Create the user record
            $user = User::create([
                'public_id' => (string) Str::uuid(),
                'name' => $request->input('full_name'),
                'email' => $request->input('email'),
                // 'username' column does not exist in the users table, omitted
                'password' => Hash::make($request->input('password')),
                // default values defined by migrations
                'status' => 'active',
                'bean_type_id' => $request->input('bean_type_id'),
                'roast_level_id' => $request->input('roast_level_id'),
            ]);

            // Create related preference row if the table exists (ignore failures gracefully)
            if (Schema::hasTable('user_preferences')) {
                DB::table('user_preferences')->insert([
                    'user_id' => $user->id,
                ]);
            }

            // Create points ledger row if the table exists
            if (Schema::hasTable('user_points')) {
                DB::table('user_points')->insert([
                    'user_id' => $user->id,
                    'balance' => 0,
                ]);
            }

            // Generate a simple email verification token (hashed for storage)
            if (Schema::hasTable('user_tokens')) {
                $rawToken = Str::random(60);
                DB::table('user_tokens')->insert([
                    'user_id' => $user->id,
                    'type' => 'email_verify',
                    'token_hash' => hash('sha256', $rawToken),
                    'expires_at' => now()->addHours(24),
                ]);
                // You could dispatch a mailable here, passing $rawToken to the user.
            }

            return $user;
        });

        // Build a generic request identifier
        $requestId = (string) Str::uuid();

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Registration pending email verification.',
                'user_public_id' => $user->public_id,
            ],
            'request_id' => $requestId,
        ], 201);
    }
}

