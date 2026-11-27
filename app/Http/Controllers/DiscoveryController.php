<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DiscoveryController extends Controller
{
    public function wishlist(Request $request): JsonResponse
    {
        $rows = DB::table('wishlists')->join('cafes', 'cafes.id', '=', 'wishlists.cafe_id')
            ->where('wishlists.user_id', $request->user()->id)->where('cafes.status', 'active')->whereNull('cafes.deleted_at')
            ->orderByDesc('wishlists.created_at')->limit(50)->get(['cafes.*', 'wishlists.created_at as wishlisted_at']);
        return $this->success($rows->map(fn ($cafe) => [
            'public_id' => $cafe->public_id, 'name' => $cafe->name, 'slug' => $cafe->slug,
            'address' => $cafe->address, 'avg_rating' => (float) $cafe->avg_rating,
            'review_count' => (int) $cafe->review_count, 'wishlisted_at' => $cafe->wishlisted_at,
        ])->all(), ['limit' => 50]);
    }

    public function addWishlist(Request $request, string $cafePublicId): JsonResponse
    {
        $cafe = $this->activeCafe($cafePublicId);
        if (!$cafe) return $this->error('NOT_FOUND', 'Cafe tidak ditemukan.', 404);

        $inserted = DB::table('wishlists')->insertOrIgnore([
            'user_id' => $request->user()->id, 'cafe_id' => $cafe->id,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        if ($inserted) $this->recordEvent($request, 'wishlist_add', (int) $cafe->id);
        return $this->success(['cafe_public_id' => $cafePublicId, 'is_wishlisted' => true], [], $inserted ? 201 : 200);
    }

    public function removeWishlist(Request $request, string $cafePublicId): JsonResponse
    {
        $cafe = $this->activeCafe($cafePublicId);
        if (!$cafe) return $this->error('NOT_FOUND', 'Cafe tidak ditemukan.', 404);
        $deleted = DB::table('wishlists')->where('user_id', $request->user()->id)->where('cafe_id', $cafe->id)->delete();
        if ($deleted) $this->recordEvent($request, 'wishlist_remove', (int) $cafe->id);
        return $this->success(['cafe_public_id' => $cafePublicId, 'is_wishlisted' => false]);
    }

    public function recentCafes(Request $request): JsonResponse
    {
        $rows = DB::table('user_cafe_views')->join('cafes', 'cafes.id', '=', 'user_cafe_views.cafe_id')
            ->where('user_cafe_views.user_id', $request->user()->id)->where('cafes.status', 'active')->whereNull('cafes.deleted_at')
            ->orderByDesc('user_cafe_views.last_viewed_at')->limit(10)
            ->get(['cafes.*', 'user_cafe_views.view_count', 'user_cafe_views.last_viewed_at']);
        return $this->success($rows->map(fn ($cafe) => [
            'public_id' => $cafe->public_id, 'name' => $cafe->name, 'slug' => $cafe->slug,
            'address' => $cafe->address, 'avg_rating' => (float) $cafe->avg_rating,
            'review_count' => (int) $cafe->review_count, 'view_count' => (int) $cafe->view_count,
            'last_viewed_at' => $cafe->last_viewed_at,
        ])->all(), ['limit' => 10]);
    }

    public function recordView(Request $request, string $cafePublicId): JsonResponse
    {
        $cafe = $this->activeCafe($cafePublicId);
        if (!$cafe) return $this->error('NOT_FOUND', 'Cafe tidak ditemukan.', 404);
        DB::statement('INSERT INTO user_cafe_views (user_id, cafe_id, view_count, first_viewed_at, last_viewed_at) VALUES (?, ?, 1, UTC_TIMESTAMP(), UTC_TIMESTAMP()) ON DUPLICATE KEY UPDATE view_count = view_count + 1, last_viewed_at = UTC_TIMESTAMP()', [$request->user()->id, $cafe->id]);
        $this->recordEvent($request, 'view', (int) $cafe->id);
        return $this->success(['cafe_public_id' => $cafePublicId, 'recorded' => true]);
    }

    public function searchHistory(Request $request): JsonResponse
    {
        $rows = DB::table('user_search_history')->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')->orderByDesc('id')->limit(10)->get(['id', 'keyword', 'cafe_id', 'created_at']);
        $cafeIds = $rows->pluck('cafe_id')->filter()->unique();
        $cafeIdsById = $cafeIds->isEmpty() ? collect() : DB::table('cafes')->whereIn('id', $cafeIds)->pluck('public_id', 'id');
        return $this->success($rows->map(fn ($row) => [
            'id' => (int) $row->id, 'keyword' => $row->keyword,
            'cafe_public_id' => $row->cafe_id ? ($cafeIdsById[$row->cafe_id] ?? null) : null,
            'created_at' => $row->created_at,
        ])->all(), ['limit' => 10]);
    }

    public function storeSearchHistory(Request $request): JsonResponse
    {
        $data = $request->validate(['keyword' => ['required', 'string', 'min:2', 'max:100']]);
        $keyword = trim($data['keyword']);
        if (mb_strlen($keyword) < 2) return $this->error('VALIDATION_ERROR', 'Keyword minimal 2 karakter.', 422);
        $userId = (int) $request->user()->id;
        DB::transaction(function () use ($keyword, $userId, $request): void {
            $existing = DB::table('user_search_history')->where('user_id', $userId)->whereRaw('LOWER(keyword) = ?', [mb_strtolower($keyword)])->orderByDesc('id')->first();
            if ($existing) {
                DB::table('user_search_history')->where('id', $existing->id)->update(['keyword' => $keyword, 'created_at' => now()]);
            } else {
                DB::table('user_search_history')->insert(['user_id' => $userId, 'keyword' => $keyword, 'created_at' => now()]);
            }
            $staleIds = DB::table('user_search_history')->where('user_id', $userId)->orderByDesc('created_at')->orderByDesc('id')->skip(20)->take(100)->pluck('id');
            if ($staleIds->isNotEmpty()) DB::table('user_search_history')->whereIn('id', $staleIds)->delete();
            $this->recordEvent($request, 'search', null, $keyword);
        });
        return $this->success(['keyword' => $keyword, 'saved' => true]);
    }

    public function deleteSearchHistory(Request $request, ?int $id = null): JsonResponse
    {
        $query = DB::table('user_search_history')->where('user_id', $request->user()->id);
        if ($id !== null) $query->where('id', $id);
        $deleted = $query->delete();
        return $this->success(['deleted_count' => $deleted]);
    }

    public function recordSearch(Request $request, string $keyword): void
    {
        $keyword = trim($keyword);
        if (mb_strlen($keyword) < 2) return;
        $userId = (int) $request->user()->id;
        DB::transaction(function () use ($keyword, $userId, $request): void {
            $existing = DB::table('user_search_history')->where('user_id', $userId)->whereRaw('LOWER(keyword) = ?', [mb_strtolower($keyword)])->orderByDesc('id')->first();
            if ($existing) DB::table('user_search_history')->where('id', $existing->id)->update(['keyword' => mb_substr($keyword, 0, 100), 'created_at' => now()]);
            else DB::table('user_search_history')->insert(['user_id' => $userId, 'keyword' => mb_substr($keyword, 0, 100), 'created_at' => now()]);
            $staleIds = DB::table('user_search_history')->where('user_id', $userId)->orderByDesc('created_at')->orderByDesc('id')->skip(20)->take(100)->pluck('id');
            if ($staleIds->isNotEmpty()) DB::table('user_search_history')->whereIn('id', $staleIds)->delete();
            $this->recordEvent($request, 'search', null, $keyword);
        });
    }

    private function activeCafe(string $publicId): ?object
    {
        return DB::table('cafes')->where('public_id', $publicId)->where('status', 'active')->whereNull('deleted_at')->first(['id', 'public_id']);
    }

    private function recordEvent(Request $request, string $type, ?int $cafeId, ?string $keyword = null): void
    {
        if (!DB::table('user_privacy_settings')->where('user_id', $request->user()->id)->value('personalization_enabled')) return;
        DB::table('user_events')->insert([
            'user_id' => $request->user()->id, 'event_type' => $type, 'cafe_id' => $cafeId,
            'query_text' => $keyword ? mb_substr($keyword, 0, 100) : null, 'created_at' => now(),
        ]);
    }
    private function success(mixed $data, array $meta = [], int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'data' => $data];
        if (!empty($meta)) {
            $payload['meta'] = $meta;
        }
        $payload['request_id'] = (string) Str::uuid();
        return response()->json($payload, $status);
    }

    private function error(string $code, string $message, int $status): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
            'request_id' => (string) Str::uuid(),
        ], $status);
    }
}
