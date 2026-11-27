<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReviewController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $cafeId = $request->query('cafe_id');
        $q = $request->query('q');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = DB::table('reviews')
            ->join('users', 'users.id', '=', 'reviews.user_id')
            ->join('cafes', 'cafes.id', '=', 'reviews.cafe_id')
            ->whereNull('reviews.deleted_at')
            ->select([
                'reviews.id',
                'reviews.public_id',
                'reviews.rating',
                'reviews.comment',
                'reviews.status',
                'reviews.rejection_reason',
                'reviews.helpful_count',
                'reviews.has_photo',
                'reviews.is_featured',
                'reviews.moderated_at',
                'reviews.created_at',
                'users.id as user_id',
                'users.public_id as user_public_id',
                'users.name as user_name',
                'users.email as user_email',
                'cafes.id as cafe_id',
                'cafes.public_id as cafe_public_id',
                'cafes.name as cafe_name',
            ]);

        if ($status) {
            $query->where('reviews.status', $status);
        }
        if ($cafeId) {
            if (strlen($cafeId) === 36) {
                $query->where('cafes.public_id', $cafeId);
            } else {
                $query->where('cafes.id', (int) $cafeId);
            }
        }
        if ($q) {
            $escaped = '%' . addcslashes($q, '%_\\') . '%';
            $query->where(function ($sub) use ($escaped) {
                $sub->where('reviews.comment', 'like', $escaped)
                    ->orWhere('users.name', 'like', $escaped)
                    ->orWhere('cafes.name', 'like', $escaped);
            });
        }
        if ($fromDate) {
            $query->where('reviews.created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $query->where('reviews.created_at', '<=', $toDate . ' 23:59:59');
        }

        $page = max((int) $request->query('page', 1), 1);
        $perPage = min(max((int) $request->query('per_page', 20), 1), 100);

        $total = $query->count();
        $rows = $query->orderByDesc('reviews.created_at')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        $reviewIds = $rows->pluck('id')->all();
        $reportCounts = [];
        $reportReasons = [];

        if (!empty($reviewIds)) {
            $reports = DB::table('review_reports')
                ->whereIn('review_id', $reviewIds)
                ->where('status', 'open')
                ->get(['review_id', 'reason']);

            foreach ($reports as $rep) {
                $reportCounts[$rep->review_id] = ($reportCounts[$rep->review_id] ?? 0) + 1;
                $reportReasons[$rep->review_id][] = $rep->reason;
            }
        }

        $items = $rows->map(function ($row) use ($reportCounts, $reportReasons) {
            return [
                'public_id' => $row->public_id,
                'cafe' => [
                    'public_id' => $row->cafe_public_id,
                    'name' => $row->cafe_name,
                ],
                'user' => [
                    'public_id' => $row->user_public_id,
                    'name' => $row->user_name,
                    'email' => $row->user_email,
                ],
                'rating' => (int) $row->rating,
                'comment' => $row->comment,
                'status' => $row->status,
                'rejection_reason' => $row->rejection_reason,
                'helpful_count' => (int) $row->helpful_count,
                'has_photo' => (bool) $row->has_photo,
                'is_featured' => (bool) $row->is_featured,
                'open_reports_count' => $reportCounts[$row->id] ?? 0,
                'report_reasons' => array_values(array_unique($reportReasons[$row->id] ?? [])),
                'moderated_at' => $row->moderated_at,
                'created_at' => $row->created_at,
            ];
        })->all();

        return response()->json([
            'success' => true,
            'data' => $items,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => (int) ceil($total / $perPage),
            ],
        ]);
    }

    public function moderate(Request $request, string $reviewPublicId): JsonResponse
    {
        $data = $request->validate([
            'action' => ['required', 'string', 'in:approve,reject'],
            'reason' => ['nullable', 'string', 'max:255', 'required_if:action,reject'],
        ]);

        $admin = $request->user();
        $review = DB::table('reviews')
            ->where('public_id', $reviewPublicId)
            ->whereNull('deleted_at')
            ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'NOT_FOUND', 'message' => 'Ulasan tidak ditemukan.'],
            ], 404);
        }

        $this->executeModeration($review, $data['action'], $data['reason'] ?? null, (int) $admin->id, $admin->role ?? 'admin', $request->ip(), (string) $request->userAgent());

        $updated = DB::table('reviews')->where('id', $review->id)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'public_id' => $updated->public_id,
                'status' => $updated->status,
                'rejection_reason' => $updated->rejection_reason,
                'moderated_at' => $updated->moderated_at,
            ],
            'message' => 'Moderasi ulasan berhasil diproses.',
        ]);
    }

    public function bulkModerate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'review_ids' => ['required', 'array', 'min:1', 'max:50'],
            'review_ids.*' => ['required', 'string'],
            'action' => ['required', 'string', 'in:approve,reject'],
            'reason' => ['nullable', 'string', 'max:255', 'required_if:action,reject'],
        ]);

        $admin = $request->user();
        $results = [];

        foreach ($data['review_ids'] as $reviewPublicId) {
            $review = DB::table('reviews')
                ->where('public_id', $reviewPublicId)
                ->whereNull('deleted_at')
                ->first();

            if (!$review) {
                $results[] = [
                    'review_id' => $reviewPublicId,
                    'success' => false,
                    'error' => 'Ulasan tidak ditemukan.',
                ];
                continue;
            }

            try {
                $this->executeModeration($review, $data['action'], $data['reason'] ?? null, (int) $admin->id, $admin->role ?? 'admin', $request->ip(), (string) $request->userAgent());
                $results[] = [
                    'review_id' => $reviewPublicId,
                    'success' => true,
                    'status' => $data['action'] === 'approve' ? 'approved' : 'rejected',
                ];
            } catch (\Throwable $e) {
                $results[] = [
                    'review_id' => $reviewPublicId,
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $results,
            'message' => 'Moderasi massal selesai diproses.',
        ]);
    }

    public function toggleFeature(Request $request, string $reviewPublicId): JsonResponse
    {
        $data = $request->validate([
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $review = DB::table('reviews')
            ->where('public_id', $reviewPublicId)
            ->whereNull('deleted_at')
            ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'NOT_FOUND', 'message' => 'Ulasan tidak ditemukan.'],
            ], 404);
        }

        $newVal = array_key_exists('is_featured', $data) ? (bool) $data['is_featured'] : !((bool) $review->is_featured);

        DB::table('reviews')->where('id', $review->id)->update([
            'is_featured' => $newVal,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'public_id' => $review->public_id,
                'is_featured' => $newVal,
            ],
            'message' => $newVal ? 'Ulasan berhasil ditandai sebagai ulasan pilihan.' : 'Tanda ulasan pilihan berhasil dicabut.',
        ]);
    }

    public function reportsIndex(Request $request): JsonResponse
    {
        $status = $request->query('status', 'open');
        $query = DB::table('review_reports')
            ->join('reviews', 'reviews.id', '=', 'review_reports.review_id')
            ->join('users as reporters', 'reporters.id', '=', 'review_reports.reporter_user_id')
            ->join('cafes', 'cafes.id', '=', 'reviews.cafe_id')
            ->select([
                'review_reports.id',
                'review_reports.reason',
                'review_reports.note',
                'review_reports.status',
                'review_reports.handled_at',
                'review_reports.created_at',
                'reporters.public_id as reporter_public_id',
                'reporters.name as reporter_name',
                'reviews.public_id as review_public_id',
                'reviews.comment as review_comment',
                'reviews.rating as review_rating',
                'reviews.status as review_status',
                'cafes.public_id as cafe_public_id',
                'cafes.name as cafe_name',
            ]);

        if ($status) {
            $query->where('review_reports.status', $status);
        }

        $page = max((int) $request->query('page', 1), 1);
        $perPage = min(max((int) $request->query('per_page', 20), 1), 100);

        $total = $query->count();
        $rows = $query->orderByDesc('review_reports.created_at')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        $items = $rows->map(function ($row) {
            return [
                'id' => (int) $row->id,
                'reason' => $row->reason,
                'note' => $row->note,
                'status' => $row->status,
                'reporter' => [
                    'public_id' => $row->reporter_public_id,
                    'name' => $row->reporter_name,
                ],
                'review' => [
                    'public_id' => $row->review_public_id,
                    'comment' => $row->review_comment,
                    'rating' => (int) $row->review_rating,
                    'status' => $row->review_status,
                ],
                'cafe' => [
                    'public_id' => $row->cafe_public_id,
                    'name' => $row->cafe_name,
                ],
                'handled_at' => $row->handled_at,
                'created_at' => $row->created_at,
            ];
        })->all();

        return response()->json([
            'success' => true,
            'data' => $items,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => (int) ceil($total / $perPage),
            ],
        ]);
    }

    public function handleReport(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:resolved,dismissed'],
        ]);

        $admin = $request->user();
        $report = DB::table('review_reports')->where('id', $id)->first();
        if (!$report) {
            return response()->json([
                'success' => false,
                'error' => ['code' => 'NOT_FOUND', 'message' => 'Laporan tidak ditemukan.'],
            ], 404);
        }

        $review = DB::table('reviews')->where('id', $report->review_id)->first();

        DB::transaction(function () use ($report, $review, $data, $admin, $request) {
            DB::table('review_reports')->where('id', $report->id)->update([
                'status' => $data['status'],
                'handled_by' => $admin->id,
                'handled_at' => now(),
                'updated_at' => now(),
            ]);

            if ($review) {
                if ($data['status'] === 'resolved') {
                    DB::table('reviews')->where('id', $review->id)->update([
                        'status' => 'rejected',
                        'rejection_reason' => 'Laporan pelanggaran ditindaklanjuti admin',
                        'moderated_by' => $admin->id,
                        'moderated_at' => now(),
                        'updated_at' => now(),
                    ]);
                    (new ReviewController())->recalcCafeRating((int) $review->cafe_id);
                } elseif ($data['status'] === 'dismissed') {
                    $otherOpenReports = DB::table('review_reports')
                        ->where('review_id', $review->id)
                        ->where('status', 'open')
                        ->exists();

                    if (!$otherOpenReports && $review->status === 'reported') {
                        DB::table('reviews')->where('id', $review->id)->update([
                            'status' => 'approved',
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            DB::table('audit_logs')->insert([
                'actor_user_id' => $admin->id,
                'actor_role' => $admin->role ?? 'admin',
                'action' => 'handle_review_report',
                'entity_type' => 'review_report',
                'entity_id' => (string) $report->id,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
                'metadata' => json_encode(['status' => $data['status'], 'review_id' => $review ? $review->id : null]),
                'created_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $id,
                'status' => $data['status'],
            ],
            'message' => 'Laporan berhasil ditangani.',
        ]);
    }

    public function dashboardSummary(): JsonResponse
    {
        $activeCafes = DB::table('cafes')->where('status', 'active')->whereNull('deleted_at')->count();
        $pendingReviews = DB::table('reviews')->where('status', 'pending')->whereNull('deleted_at')->count();
        $reportedReviews = DB::table('reviews')->where('status', 'reported')->whereNull('deleted_at')->count();
        $openReports = DB::table('review_reports')->where('status', 'open')->count();
        $pendingPartnerships = DB::table('partnership_requests')->where('status', 'pending')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'active_cafes' => $activeCafes,
                'pending_reviews' => $pendingReviews,
                'reported_reviews' => $reportedReviews,
                'open_reports' => $openReports,
                'pending_partnerships' => $pendingPartnerships,
            ],
        ]);
    }

    private function executeModeration(object $review, string $action, ?string $reason, int $adminId, string $adminRole, ?string $ip, ?string $userAgent): void
    {
        DB::transaction(function () use ($review, $action, $reason, $adminId, $adminRole, $ip, $userAgent) {
            $now = now();
            if ($action === 'approve') {
                DB::table('reviews')->where('id', $review->id)->update([
                    'status' => 'approved',
                    'moderated_by' => $adminId,
                    'moderated_at' => $now,
                    'rejection_reason' => null,
                    'updated_at' => $now,
                ]);

                // Berikan poin jika belum pernah diberikan untuk pasangan user + cafe ini
                $idemKey = 'review:' . $review->user_id . ':' . $review->cafe_id;
                $alreadyAwarded = DB::table('points_ledger')->where('idempotency_key', $idemKey)->exists();

                if (!$alreadyAwarded) {
                    $earnedPoints = 0;
                    $earnedPoints += (int) (DB::table('point_rules')->where('code', 'review_rating')->where('is_active', true)->value('points') ?? 5);

                    $minCommentLen = (int) (DB::table('app_settings')->where('setting_key', 'review_min_comment_length')->value('setting_value') ?? 30);
                    if ($review->comment && mb_strlen(trim($review->comment)) >= $minCommentLen) {
                        $earnedPoints += (int) (DB::table('point_rules')->where('code', 'review_comment')->where('is_active', true)->value('points') ?? 10);
                    }

                    if ($review->has_photo) {
                        $earnedPoints += (int) (DB::table('point_rules')->where('code', 'review_photo')->where('is_active', true)->value('points') ?? 15);
                    }

                    if ($earnedPoints > 0) {
                        $userPoints = DB::table('user_points')->where('user_id', $review->user_id)->lockForUpdate()->first();
                        $newBalance = ($userPoints ? (int) $userPoints->balance : 0) + $earnedPoints;
                        $newLifetime = ($userPoints ? (int) $userPoints->lifetime_earned : 0) + $earnedPoints;

                        if ($userPoints) {
                            DB::table('user_points')->where('user_id', $review->user_id)->update([
                                'balance' => $newBalance,
                                'lifetime_earned' => $newLifetime,
                                'updated_at' => $now,
                            ]);
                        } else {
                            DB::table('user_points')->insert([
                                'user_id' => $review->user_id,
                                'balance' => $newBalance,
                                'lifetime_earned' => $newLifetime,
                                'updated_at' => $now,
                            ]);
                        }

                        DB::table('points_ledger')->insert([
                            'user_id' => $review->user_id,
                            'entry_type' => 'earn',
                            'points' => $earnedPoints,
                            'balance_after' => $newBalance,
                            'source_type' => 'review',
                            'source_id' => $review->id,
                            'idempotency_key' => $idemKey,
                            'note' => 'Poin ulasan disetujui',
                            'created_at' => $now,
                        ]);
                    }
                }

                (new ReviewController())->recalcCafeRating((int) $review->cafe_id);

                DB::table('notifications')->insert([
                    'user_id' => $review->user_id,
                    'type' => 'review_approved',
                    'title' => 'Ulasan Disetujui',
                    'body' => 'Ulasan Anda telah disetujui dan kini dapat dilihat oleh pengguna lain.',
                    'data' => json_encode(['review_id' => $review->public_id, 'cafe_id' => $review->cafe_id]),
                    'created_at' => $now,
                ]);

                DB::table('audit_logs')->insert([
                    'actor_user_id' => $adminId,
                    'actor_role' => $adminRole,
                    'action' => 'review_approve',
                    'entity_type' => 'review',
                    'entity_id' => (string) $review->id,
                    'ip_address' => $ip,
                    'user_agent' => substr((string) $userAgent, 0, 255),
                    'metadata' => json_encode(['review_public_id' => $review->public_id, 'action' => 'approve']),
                    'created_at' => $now,
                ]);
            } else {
                DB::table('reviews')->where('id', $review->id)->update([
                    'status' => 'rejected',
                    'moderated_by' => $adminId,
                    'moderated_at' => $now,
                    'rejection_reason' => $reason,
                    'updated_at' => $now,
                ]);

                // Jika sebelumnya sudah dapat poin dan berstatus approved, revoke bila perlu
                $idemKey = 'review:' . $review->user_id . ':' . $review->cafe_id;
                $previousEarn = DB::table('points_ledger')
                    ->where('idempotency_key', $idemKey)
                    ->where('entry_type', 'earn')
                    ->first();

                $revokeIdemKey = 'revoke:' . $idemKey;
                $alreadyRevoked = DB::table('points_ledger')->where('idempotency_key', $revokeIdemKey)->exists();

                if ($previousEarn && !$alreadyRevoked) {
                    $pointsToRevoke = (int) $previousEarn->points;
                    $userPoints = DB::table('user_points')->where('user_id', $review->user_id)->lockForUpdate()->first();
                    $currentBal = $userPoints ? (int) $userPoints->balance : 0;
                    $newBalance = max(0, $currentBal - $pointsToRevoke);

                    if ($userPoints) {
                        DB::table('user_points')->where('user_id', $review->user_id)->update([
                            'balance' => $newBalance,
                            'updated_at' => $now,
                        ]);
                    }

                    DB::table('points_ledger')->insert([
                        'user_id' => $review->user_id,
                        'entry_type' => 'revoke',
                        'points' => -$pointsToRevoke,
                        'balance_after' => $newBalance,
                        'source_type' => 'review',
                        'source_id' => $review->id,
                        'idempotency_key' => $revokeIdemKey,
                        'note' => 'Pencabutan poin karena ulasan ditolak',
                        'created_at' => $now,
                    ]);
                }

                (new ReviewController())->recalcCafeRating((int) $review->cafe_id);

                DB::table('notifications')->insert([
                    'user_id' => $review->user_id,
                    'type' => 'review_rejected',
                    'title' => 'Ulasan Ditolak',
                    'body' => 'Ulasan Anda tidak dapat disetujui: ' . $reason,
                    'data' => json_encode(['review_id' => $review->public_id, 'reason' => $reason]),
                    'created_at' => $now,
                ]);

                DB::table('audit_logs')->insert([
                    'actor_user_id' => $adminId,
                    'actor_role' => $adminRole,
                    'action' => 'review_reject',
                    'entity_type' => 'review',
                    'entity_id' => (string) $review->id,
                    'ip_address' => $ip,
                    'user_agent' => substr((string) $userAgent, 0, 255),
                    'metadata' => json_encode(['review_public_id' => $review->public_id, 'action' => 'reject', 'reason' => $reason]),
                    'created_at' => $now,
                ]);
            }
        });
    }
}