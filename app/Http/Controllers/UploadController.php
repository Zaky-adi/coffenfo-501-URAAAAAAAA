<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function presign(Request $request): JsonResponse
    {
        $user = $request->user();
        $rateKey = 'upload_presign:' . $user->id;
        if (RateLimiter::tooManyAttempts($rateKey, 30)) {
            return $this->error('RATE_LIMITED', 'Batas permintaan presigned upload tercapai (maks 30/jam).', 429);
        }

        $pendingCount = DB::table('uploads')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->count();
        if ($pendingCount >= 5) {
            return $this->error('UPLOAD_LIMIT_EXCEEDED', 'Maksimal 5 upload berstatus pending yang menunggu konfirmasi.', 422);
        }

        $data = $request->validate([
            'purpose' => ['required', 'string', 'in:review_photo,cafe_photo,menu_photo,avatar'],
            'content_type' => ['required', 'string', 'in:image/jpeg,image/png,image/webp'],
            'size_bytes' => ['required', 'integer', 'min:1', 'max:5242880'],
        ]);

        $publicId = (string) Str::uuid();
        $ext = match ($data['content_type']) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'bin',
        };
        $objectKey = 'uploads/' . $data['purpose'] . '/' . $publicId . '.' . $ext;
        $expiresAt = now()->addMinutes(10);
        $uploadUrl = url('/api/v1/uploads/' . $publicId . '/direct-put');

        DB::table('uploads')->insert([
            'public_id' => $publicId,
            'user_id' => $user->id,
            'purpose' => $data['purpose'],
            'object_key' => $objectKey,
            'content_type' => $data['content_type'],
            'declared_size' => $data['size_bytes'],
            'status' => 'pending',
            'expires_at' => $expiresAt,
            'created_at' => now(),
        ]);

        RateLimiter::hit($rateKey, 3600);

        return $this->success([
            'upload_id' => $publicId,
            'upload_url' => $uploadUrl,
            'method' => 'PUT',
            'headers' => [
                'Content-Type' => $data['content_type'],
            ],
            'expires_at' => $expiresAt->toIso8601String(),
        ], [], 201);
    }

    public function confirm(Request $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'upload_id' => [ 'required', 'string', 'uuid' ],
        ]);

        $upload = DB::table('uploads')
            ->where('public_id', $data['upload_id'])
            ->where('user_id', $user->id)
            ->first();

        if (!$upload) {
            return $this->error('NOT_FOUND', 'Data upload tidak ditemukan.', 404);
        }

        if ($upload->status === 'ready') {
            return $this->success([
                'upload_id' => $upload->public_id,
                'status' => 'ready',
                'url' => $upload->url,
                'message' => 'Upload sudah siap digunakan.',
            ]);
        }

        if (now()->greaterThan($upload->expires_at)) {
            DB::table('uploads')->where('id', $upload->id)->update([
                'status' => 'expired',
                'rejection_reason' => 'Waktu upload telah kedaluwarsa.',
            ]);
            return $this->error('UPLOAD_EXPIRED', 'Waktu upload telah kedaluwarsa.', 422);
        }

        $publicUrl = url('/storage/' . $upload->object_key);
        DB::table('uploads')->where('id', $upload->id)->update([
            'status' => 'ready',
            'url' => $publicUrl,
            'size_bytes' => $upload->declared_size,
            'confirmed_at' => now(),
        ]);

        return $this->success([
            'upload_id' => $upload->public_id,
            'status' => 'ready',
            'url' => $publicUrl,
            'message' => 'Upload berhasil dikonfirmasi dan siap digunakan.',
        ]);
    }

    public function show(Request $request, string $uploadPublicId): JsonResponse
    {
        $user = $request->user();
        $upload = DB::table('uploads')
            ->where('public_id', $uploadPublicId)
            ->where('user_id', $user->id)
            ->first();

        if (!$upload) {
            return $this->error('NOT_FOUND', 'Data upload tidak ditemukan.', 404);
        }

        return $this->success([
            'upload_id' => $upload->public_id,
            'purpose' => $upload->purpose,
            'content_type' => $upload->content_type,
            'status' => $upload->status,
            'url' => $upload->url,
            'consumed' => !empty($upload->consumed_at),
            'expires_at' => $upload->expires_at,
            'confirmed_at' => $upload->confirmed_at,
        ]);
    }

    public function attachReviewPhotos(Request $request, string $reviewPublicId): JsonResponse
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
            'photos' => ['required', 'array', 'min:1', 'max:5'],
            'photos.*.upload_id' => ['required', 'string', 'uuid'],
            'photos.*.caption' => ['nullable', 'string', 'max:150'],
        ]);

        $uploadIds = array_column($data['photos'], 'upload_id');
        $uploads = DB::table('uploads')
            ->whereIn('public_id', $uploadIds)
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('public_id');

        foreach ($data['photos'] as $photo) {
            $uId = $photo['upload_id'];
            if (!isset($uploads[$uId])) {
                return $this->error('INVALID_UPLOAD', "Upload ID ${$uId} tidak valid atau bukan milik Anda.", 422);
            }
            if ($uploads[$uId]->status !== 'ready') {
                return $this->error('UPLOAD_NOT_READY', "Upload ID ${$uId} belum berstatus ready.", 422);
            }
            if (!empty($uploads[$uId]->consumed_at)) {
                return $this->error('UPLOAD_ALREADY_CONSUMED', "Upload ID {} sudah pernah digunakan.", 422);
            }
            if ($uploads[$uId]->purpose !== 'review_photo') {
                return $this->error('INVALID_PURPOSE', "Upload ID {$uId} bukan ditujukan untuk review_photo.", 422);
            }
        }

        $existingPhotosCount = DB::table('review_photos')->where('review_id', $review->id)->count();
        if ((($existingPhotosCount ?? 0) + count($data['photos'])) > 5) {
            return $this->error('PHOTO_LIMIT_EXCEEDED', 'Maksimal 5 foto per ulasan tercapai.', 422);
        }

        $inserted = [];
        DB::transaction(function () use ($data, $uploads, $review, &$inserted): void {
            foreach ($data['photos'] as $item) {
                $up = $uploads[$item['upload_id']];
                $path = $up->url ? $up->url : ('/storage/' . $up->object_key);
                $id = DB::table('review_photos')->insertGetId([
                    'review_id' => $review->id,
                    'path' => $path,
                    'caption' => $item['caption'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('uploads')->where('id', $up->id)->update([
                    'consumed_at' => now(),
                ]);

                $inserted[] = [
                    'id' => $id,
                    'upload_id' => $up->public_id,
                    'url' => $path,
                    'caption' => $item['caption'] ?? null,
                ];
            }

            DB::table('reviews')->where('id', $review->id)->update([
                'has_photo' => true,
                'updated_at' => now(),
            ]);
        });

        return $this->success([
            'review_public_id' => $reviewPublicId,
            'photos' => $inserted,
            'message' => 'Foto ulasan berhasil dilampirkan.',
        ], [], 201);
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
