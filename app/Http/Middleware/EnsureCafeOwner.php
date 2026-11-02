<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnsureCafeOwner
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()?->role !== 'owner') {
            return response()->json(['success' => false, 'error' => ['code' => 'FORBIDDEN', 'message' => 'Akses ditolak.', 'details' => []], 'request_id' => (string) Str::uuid()], 403);
        }
        return $next($request);
    }
}
