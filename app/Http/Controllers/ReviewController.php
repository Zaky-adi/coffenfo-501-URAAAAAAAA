<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    public function index(Request $request, string $cafePublicId): JsonResponse
    {
        $cafe = $this->resolveActiveCafe($cafePublicId);
        if (!$cafe) {
            return $this->error('NOT_FOUND', 'Cafe tidak ditemukan.', 404);
        }

        $query = DB::table('reviews')
            ->join('users', 'users.id', '=', 'reviews.user_id')
            ->where('reviews.cafe_id', $cafe->id)
            ->where('reviews.status', 'approved')
            ->whereNull('reviews.deleted_at');

        if ($request->filled('rating')) {
            $rating = (int) $request->query('rating');
            if ($rating >= 1 && $rating <= 5) {
                $query->where('reviews.rating', $rating);
            }
        }

        if ($request->boolean('has_photo')) {
            $query->where('reviews.has_photo', true);
        }

        $sort = $request->query('sort', 'newest');
        match ($sort) {
            'highest' => $query->orderByDesc('reviews.rating')->orderByDesc('reviews.created_at'),
            'lowest' => $query->orderBy('reviews.rating')->orderByDesc('reviews.created_at'),
            'helpful' => $query->orderByDesc('reviews.helpful_count')->orderByDesc('reviews.created_at'),
            default => $query->orderByDesc('reviews.created_at'),
        };

        $page = max((int) $request->query('page', 1), 1);
        $perPage = min(max((int) $request->query('per_page', 10), 1), 50);

        $total = $query->count();
        $rows = $query->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get([
                'reviews.id',
                'reviews.public_id',
                'reviews.rating',
                'reviews.comment',
                'reviews.helpful_count',
                'reviews.is_anonymous',
                'reviews.has_photo',
                'reviews.created_at',
                'users.public_id as user_public_id',
                'users.name as user_name'
            ]);

        $items = $rows->map(function ($row) {
            $isAnonymous = (bool) $row->is_anonymous;
            return [
                'public_id' => $row->public_id,
                'rating' => (int) $row->rating,
                'comment' => $row->comment,
                'helpful_count' => (int) $row->helpful_count,
                'has_photo' => (bool) $row->has_photo,
                'user' => $isAnonymous ? [
                    'name' => 'Pengguna Anonim',
                ] : [
                    'public_id' => $row->user_public_id,
                    'name' => $row->user_name,
                ],
                'created_at' => $row->created_at,
            ];
        })->all();

        return $this->success($items, [
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int) ceil($total / $perPage),
        ]);
    }

    public function ratingSummary(string $cafePublicId): JsonResponse
    {
        $cafe = $this->resolveActiveCafe($cafePublicId);
        if (!$cafe) {
            return $this->error('NOT_FOUND', 'Cafe tidak ditemukan.', 404);
        }

        $distributionRows = DB::table('reviews')
            ->select('rating', DB::raw('count(*) as count'))
            ->where('cafe_id', $cafe->id)
            ->where('status', 'approved')
            ->whereNull('deleted_at')
            ->groupBy('rating')
            ->get();

        $distribution = [
            '5' => 0,
            '4' => 0,
            '3' => 0,
            '2' => 0,
            '1' => 0,
        ];

        $totalReviews = 0;
        $sumRating = 0;

        foreach ($distributionRows as $row) {
            $r = (string) $row->rating;
            $cnt = (int) $row->count;
            if (isset($distribution[$r])) {
                $distribution[$r] = $cnt;
            }
            $totalReviews += $cnt;
            $sumRating += ((int) $r * $cnt);
        }

        $avgRating = $totalReviews > 0 ? round($sumRating / $totalReviews, 2) : 0.0;

        return $this->success([
            'cafe_public_id' => $cafe->public_id,
            'avg_rating' => (float) $avgRating,
            'total_reviews' => $totalReviews,
            'rating_distribution' => $distribution,
        ]);
    }

    private function resolveActiveCafe(string $publicId): ?object
    {
        return DB::table('cafes')
            ->where('public_id', $publicId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->first(['id', 'public_id']);
    }

    private function success(mixed $data, array $meta = [], int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'data' => $data];
        if (!empty($meta)) {
            $payload['meta'] = $meta;
        }
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
        ], $status);
    }

    public function store(Request $request, string $cafePublicId): JsonResponse
    {
        $user = $request->user();
        if (!$user->email_verified_at) {
            return $this->error('EMAIL_NOT_VERIFIED', 'Anda harus memverifikasi email terlebih dahulu sebelum menulis review.', 403);
        }

        $cafe = $this->resolveActiveCafe($cafePublicId);
        if (!$cafe) {
            return $this->error('NOT_FOUND', 'Cafe tidak ditemukan.', 404);
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
            'is_anonymous' => ['nullable', 'boolean'],
            'visit_id' => ['nullable', 'integer'],
        ]);

        $hasVisit = DB::table('visits')
            ->where('user_id', $user->id)
            ->where('cafe_id', $cafe->id)
            ->exists();

        if (!$hasVisit) {
            return $this->error('VISIT_REQUIRED', 'Anda harus pernah mengunjungi kafe ini sebelum dapat memberikan ulasan.', 403);
        }

        $existingReview = DB::table('reviews')
            ->where('user_id', $user->id)
            ->where('cafe_id', $cafe->id)
            ->whereNull('deleted_at')
            ->first();

        if ($existingReview) {
            return $this->error('REVIEW_EXISTS', 'Anda sudah memberikan review untuk kafe ini.', 409);
        }

        $rateKey = 'review_store:' . $user->id;
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($rateKey, 10)) {
            return $this->error('TOO_MANY_REQUESTS', 'Batas maksimal 10 review per hari tercapai.', 429);
        }

        $idemKey = $request->header('Idempotency-Key');
        $requestHash = hash('sha256', json_encode($data) . $cafePublicId);

        if ($idemKey) {
            $idemCheck = $this->handleIdempotency((string) $user->id, $idemKey, '/api/v1/cafes/{id}/reviews', $requestHash);
            if ($idemCheck['status'] === 'replay') {
                return response()->json($idemCheck['body'], $idemCheck['code']);
            }
            if ($idemCheck['status'] === 'mismatch') {
                return $this->error('IDEMPOTENCY_MISMATCH', 'Payload berbeda untuk Idempotency-Key yang sama.', 422);
            }
            if ($idemCheck['status'] === 'progress') {
                return $this->error('CONCURRENT_REQUEST', 'Permintaan sedang diproses.', 409);
            }
        }

        $reviewPublicId = Str::uuid()->toString();

        $insertedId = DB::table('reviews')->insertGetId([
            'public_id' => $reviewPublicId,
            'user_id' => $user->id,
            'cafe_id' => $cafe->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'is_anonymous' => $data['is_anonymous'] ?? false,
            'status' => 'pending',
            'helpful_count' => 0,
            'has_photo' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\RateLimiter::hit($rateKey, 86400);

        $responseData = [
            'public_id' => $reviewPublicId,
            'cafe_public_id' => $cafe->public_id,
            'rating' => (int) $data['rating'],
            'comment' => $data['comment'] ?? null,
            'status' => 'pending',
            'message' => 'Ulasan berhasil dikirim dan menunggu moderasi admin.',
        ];

        $payload = ['success' => true, 'data' => $responseData, 'request_id' => $reviewPublicId];
        if ($idemKey) {
            $this->saveIdempotency((int) $user->id, $idemKey, '/api/v1/cafes/{id}/reviews', $requestHash, 201, $payload);
        }

        return response()->json($payload, 201);
    }

    public function update(Request $request, string $reviewPublicId): JsonResponse
    {
        $user = $request->user();
        $review = DB::table('reviews')
            ->where('public_id', $reviewPublicId)
            ->where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->first();
        if (!$review) {
            return $this->error('NOT_FOUND', 'Ulasan tidak ditemukan.', 404);
        }

        $data = $request->validate([
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
            'is_anonymous' => ['nullable', 'boolean'],
        ]);

        $updates = [
            'status' => 'pending',
            'updated_at' => now(),
        ];
        if (isset($data['rating'])) $updates['rating'] = $data['rating'];
        if (array_key_exists('comment', $data)) $updates['comment'] = $data['comment'];
        if (isset($data['is_anonymous'])) $updates['is_anonymous'] = (bool) $data['is_anonymous'];

        DB::table('reviews')->where('id', $review->id)->update($updates);
        $this->recalcCafeRating((int) $review->cafe_id);

        return $this->success([
            'public_id' => $reviewPublicId,
            'status' => 'pending',
            'message' => 'Ulasan berhasil diubah dan kembali ke antrean moderasi.',
        ]);
    }

    public function destroy(Request $request, string $reviewPublicId): JsonResponse
    {
        $user = $request->user();
        $review = DB::table('reviews')
            ->where('public_id', $reviewPublicId)
            ->where('user_id', $user->id)
            ->whereNull('deleted_at')
            ->first();
        if (!$review) {
            return $this->error('NOT_FOUND', 'Ulasan tidak ditemukan.', 404);
        }

        DB::table('reviews')->where('id', $review->id)->update([
            'deleted_at' => now(),
            'updated_at' => now(),
        ]);
        $this->recalcCafeRating((int) $review->cafe_id);

        return $this->success(['deleted' => true, 'message' => 'Ulasan berhasil dihapus.']);
    }

    public function voteHelpful(Request $request, string $reviewPublicId): JsonResponse
    {
        $user = $request->user();
        $review = DB::table('reviews')
            ->where('public_id', $reviewPublicId)
            ->whereNull('deleted_at')
            ->first();
        if (!$review) {
            return $this->error('NOT_FOUND', 'Ulasan tidak ditemukan.', 404);
        }

        if ((int) $review->user_id === (int) $user->id) {
            return $this->error('CANNOT_VOTE_OWN_REVIEW', 'Tidak dapat menandai bermanfaat pada ulasan sendiri.', 403);
        }

        $inserted = DB::table('review_helpful_votes')->insertOrIgnore([
            'review_id' => $review->id,
            'user_id' => $user->id,
            'created_at' => now(),
        ]);

        if ($inserted) {
            DB::table('reviews')->where('id', $review->id)->increment('helpful_count');
        }

        $helpfulCount = (int) DB::table('reviews')->where('id', $review->id)->value('helpful_count');

        return $this->success([
            'review_public_id' => $review->public_id,
            'is_helpful' => true,
            'helpful_count' => $helpfulCount,
        ]);
    }

    public function removeHelpful(Request $request, string $reviewPublicId): JsonResponse
    {
        $user = $request->user();
        $review = DB::table('reviews')
            ->where('public_id', $reviewPublicId)
            ->whereNull('deleted_at')
            ->first();
        if (!$review) {
            return $this->error('NOT_FOUND', 'Ulasan tidak ditemukan.', 404);
        }

        $deleted = DB::table('review_helpful_votes')
            ->where('review_id', $review->id)
            ->where('user_id', $user->id)
            ->delete();

        if ($deleted) {
            DB::table('reviews')
                ->where('id', $review->id)
                ->where('helpful_count', '>', 0)
                ->decrement('helpful_count');
        }

        $helpfulCount = (int) DB::table('reviews')->where('id', $review->id)->value('helpful_count');

        return $this->success([
            'review_public_id' => $review->public_id,
            'is_helpful' => false,
            'helpful_count' => $helpfulCount,
        ]);
    }

    public function report(Request $request, string $reviewPublicId): JsonResponse
    {
        $user = $request->user();
        $review = DB::table('reviews')
            ->where('public_id', $reviewPublicId)
            ->whereNull('deleted_at')
            ->first();
        if (!$review) {
            return $this->error('NOT_FOUND', 'Ulasan tidak ditemukan.', 404);
        }

        if ((int) $review->user_id === (int) $user->id) {
            return $this->error('CANNOT_REPORT_OWN_REVIEW', 'Tidak dapat melaporkan ulasan sendiri.', 403);
        }

        $rateKey = 'review_report:' . $user->id;
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($rateKey, 20)) {
            return $this->error('TOO_MANY_REQUESTS', 'Batas maksimal 20 laporan per hari tercapai.', 429);
        }

        $data = $request->validate([
            'reason' => ['required', 'string', 'in:spam,offensive,irrelevant,inappropriate,other'],
            'note' => ['nullable', 'string', 'max:300'],
        ]);

        $alreadyReported = DB::table('review_reports')
            ->where('review_id', $review->id)
            ->where('reporter_user_id', $user->id)
            ->exists();
        if ($alreadyReported) {
            return $this->error('ALREADY_REPORTED', 'Anda sudah pernah melaporkan ulasan ini.', 409);
        }

        DB::transaction(function () use ($review, $user, $data) {
            DB::table('review_reports')->insert([
                'review_id' => $review->id,
                'reporter_user_id' => $user->id,
                'reason' => $data['reason'],
                'note' => $data['note'] ?? null,
                'status' => 'open',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $distinctReportersCount = DB::table('review_reports')
                ->where('review_id', $review->id)
                ->where('status', 'open')
                ->distinct()
                ->count('reporter_user_id');

            $hideThreshold = (int) (DB::table('app_settings')->where('setting_key', 'review_report_hide_threshold')->value('setting_value') ?? 3);

            if ($distinctReportersCount >= $hideThreshold) {
                DB::table('reviews')->where('id', $review->id)->update([
                    'status' => 'rejected',
                    'rejection_reason' => 'Akumulasi ' . $distinctReportersCount . ' laporan pengguna',
                    'updated_at' => now(),
                ]);
                $this->recalcCafeRating((int) $review->cafe_id);
            } elseif ($review->status === 'approved') {
                DB::table('reviews')->where('id', $review->id)->update([
                    'status' => 'reported',
                    'updated_at' => now(),
                ]);
            }
        });

        \Illuminate\Support\Facades\RateLimiter::hit($rateKey, 86400);

        return $this->success([
            'review_public_id' => $review->public_id,
            'message' => 'Laporan ulasan berhasil dikirim dan akan ditinjau oleh tim kami.',
        ], [], 201);
    }

    public function recalcCafeRating(int $cafeId): void
    {
        if (DB::getDriverName() === 'mysql') {
            try {
                DB::statement('CALL sp_recalc_cafe_rating(?)', [$cafeId]);
                return;
            } catch (\Throwable $e) {}
        }
        $rows = DB::table('reviews')
            ->where('cafe_id', $cafeId)
            ->whereIn('status', ['approved', 'reported'])
            ->whereNull('deleted_at')
            ->selectRaw('ROUND(AVG(rating), 2) as avg_r, COUNT(*) as cnt')
            ->first();
        DB::table('cafes')->where('id', $cafeId)->update([
            'avg_rating' => $rows ? (float) ($rows->avg_r ?? 0) : 0,
            'review_count' => $rows ? (int) ($rows->cnt ?? 0) : 0,
        ]);
    }

    private function handleIdempotency(string $userId, string $key, string $endpoint, string $hash): array
    {
        $row = DB::table('idempotency_keys')
            ->where('user_id', $userId)
            ->where('idem_key', $key)
            ->where('endpoint', $endpoint)
            ->first();
        if (!$row) return ['status' => 'new'];
        if (!hash_equals($row->request_hash, $hash)) return ['status' => 'mismatch'];
        if ($row->status === 'in_progress') return ['status' => 'progress'];
        return [
            'status' => 'replay',
            'code' => (int) $row->response_status,
            'body' => json_decode($row->response_body, true),
        ];
    }

    private function saveIdempotency(int $userId, string $key, string $endpoint, string $hash, int $code, array $body): void
    {
        DB::table('idempotency_keys')->insertOrIgnore([
            'user_id' => $userId,
            'idem_key' => $key,
            'endpoint' => $endpoint,
            'request_hash' => $hash,
            'status' => 'resolved',
            'response_status' => $code,
            'response_body' => json_encode($body, JSON_UNESCAPED_UNICODE),
            'expires_at' => now()->addDays(1),
            'created_at' => now(),
        ]);
    }
}