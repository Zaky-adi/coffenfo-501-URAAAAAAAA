<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PartnershipController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $key = trim((string) $request->header('Idempotency-Key'));
        if ($key === '' || strlen($key) > 80) return $this->error('IDEMPOTENCY_KEY_REQUIRED', 'Header Idempotency-Key wajib diisi.', 400);
        $rateKey = 'partnership:' . ($request->ip() ?? 'unknown');
        if (RateLimiter::tooManyAttempts($rateKey, 3)) return $this->error('RATE_LIMITED', 'Batas pengiriman permintaan tercapai.', 429);
        $data = $request->validate([
            'cafe_name' => ['required', 'string', 'min:2', 'max:150'],
            'owner_name' => ['required', 'string', 'min:2', 'max:120'],
            'whatsapp' => ['required', 'string', 'max:20', 'regex:/^(08[0-9]{8,13}|\+?628[0-9]{8,13})$/'],
            'area_id' => ['required', 'integer', Rule::exists('areas', 'id')->where('is_active', true)],
        ]);
        $phone = preg_replace('/^0/', '62', ltrim($data['whatsapp'], '+'));
        $data['whatsapp'] = $phone;
        $hash = hash('sha256', json_encode($data, JSON_THROW_ON_ERROR));
        $response = DB::transaction(function () use ($request, $key, $hash, $data, $phone): array {
            $existing = DB::table('partnership_requests')->where('idempotency_key', $key)->lockForUpdate()->first();
            if ($existing) {
                $existingHash = hash('sha256', json_encode([
                    'cafe_name' => $existing->cafe_name, 'owner_name' => $existing->owner_name,
                    'whatsapp' => $existing->whatsapp, 'area_id' => $existing->area_id,
                ], JSON_THROW_ON_ERROR));
                if (!hash_equals($existingHash, $hash)) return ['error' => true, 'status' => 422];
                return ['status' => 201, 'body' => ['success' => true, 'data' => ['message' => 'Permintaan kemitraan berhasil dikirim.', 'public_id' => $existing->public_id], 'request_id' => $existing->public_id], 'replay' => true];
            }
            $publicId = (string) Str::uuid();
            $id = DB::table('partnership_requests')->insertGetId([
                'cafe_name' => $data['cafe_name'], 'owner_name' => $data['owner_name'], 'whatsapp' => $phone,
                'public_id' => $publicId, 'area_id' => $data['area_id'], 'ip_address' => $request->ip(), 'idempotency_key' => $key, 'created_at' => now(), 'updated_at' => now(),
            ]);
            $body = ['success' => true, 'data' => ['message' => 'Permintaan kemitraan berhasil dikirim.', 'public_id' => $publicId], 'request_id' => $publicId];
            return ['status' => 201, 'body' => $body];
        });
        if (!empty($response['error'])) return $this->error($response['status'] === 422 ? 'VALIDATION_ERROR' : 'REQUEST_IN_PROGRESS', 'Permintaan idempoten tidak dapat diproses.', $response['status']);
        RateLimiter::hit($rateKey, 3600);
        return response()->json($response['body'], $response['status']);
    }

    public function index(Request $request): JsonResponse
    {
        $data = $request->validate(['status' => ['sometimes', 'in:new,contacted,approved,rejected'], 'cursor' => ['sometimes', 'string', 'max:80']]);
        $query = DB::table('partnership_requests')->orderByDesc('id');
        if (isset($data['status'])) $query->where('status', $data['status']);
        if (isset($data['cursor'])) {
            $rawCursor = base64_decode(strtr($data['cursor'], '-_', '+/'), true);
            if ($rawCursor === false || !ctype_digit($rawCursor)) return $this->error('INVALID_CURSOR', 'Cursor tidak valid.', 400);
            $query->where('id', '<', (int) $rawCursor);
        }
        $rows = $query->limit(51)->get();
        $hasMore = $rows->count() > 50;
        $items = $rows->take(50)->map(fn ($row) => [
            'public_id' => $row->public_id, 'cafe_name' => $row->cafe_name, 'owner_name' => $row->owner_name, 'whatsapp' => $row->whatsapp,
            'area_id' => $row->area_id, 'status' => $row->status, 'admin_note' => $row->admin_note, 'created_at' => $row->created_at,
        ])->all();
        $lastId = $hasMore ? $rows->take(50)->last()->id : null;
        return response()->json(['success' => true, 'data' => $items, 'meta' => ['limit' => 50, 'has_more' => $hasMore, 'next_cursor' => $lastId ? rtrim(strtr(base64_encode((string) $lastId), '+/', '-_'), '=') : null], 'request_id' => (string) Str::uuid()]);
    }

    public function update(Request $request, string $publicId): JsonResponse
    {
        $data = $request->validate(['status' => ['required', 'in:contacted,approved,rejected'], 'admin_note' => ['sometimes', 'nullable', 'string', 'max:500']]);
        $row = DB::table('partnership_requests')->where('public_id', $publicId)->first();
        if (!$row) return $this->error('NOT_FOUND', 'Permintaan kemitraan tidak ditemukan.', 404);
        DB::transaction(function () use ($request, $data, $row): void {
            DB::table('partnership_requests')->where('id', $row->id)->update($data + ['reviewed_by' => $request->user()->id, 'updated_at' => now()]);
            DB::table('audit_logs')->insert([
                'actor_user_id' => $request->user()->id, 'actor_role' => $request->user()->role,
                'action' => 'partnership.update', 'entity_type' => 'partnership_request', 'entity_id' => $row->public_id,
                'ip_address' => $request->ip(), 'user_agent' => substr((string) $request->userAgent(), 0, 255),
                'metadata' => json_encode($data, JSON_THROW_ON_ERROR), 'created_at' => now(),
            ]);
        });
        return response()->json(['success' => true, 'data' => ['public_id' => $publicId] + $data, 'request_id' => (string) Str::uuid()]);
    }

    private function error(string $code, string $message, int $status): JsonResponse
    {
        return response()->json(['success' => false, 'error' => ['code' => $code, 'message' => $message, 'details' => []], 'request_id' => (string) Str::uuid()], $status);
    }
}
