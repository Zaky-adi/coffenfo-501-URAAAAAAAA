<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()?->role !== 'admin') {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'FORBIDDEN', 'message' => 'Akses ditolak.', 'details' => []],
                'request_id' => (string) \Illuminate\Support\Str::uuid(),
            ], 403);
        }

        return $next($request);
    }
}
