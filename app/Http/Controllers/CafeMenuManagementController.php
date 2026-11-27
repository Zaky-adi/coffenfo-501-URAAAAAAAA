<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CafeMenuManagementController extends Controller
{
    public function index(Request $request, string $cafePublicId): JsonResponse
    {
        $cafe = $this->getOwnerCafe($request, $cafePublicId);
        if (!$cafe) return $this->error('NOT_FOUND', 'Cafe tidak ditemukan atau bukan milik Anda.', 404);

        $query = DB::table('menu_items')
            ->join('menu_categories', 'menu_items.category_id', '=', 'menu_categories.id')
            ->where('menu_items.cafe_id', $cafe->id)
            ->where('menu_items.is_active', true);

        if ($request->filled('category')) {
            $query->where('menu_categories.slug', (string) $request->query('category'));
        }

        $items = $query->orderBy('menu_categories.sort_order')
            ->orderBy('menu_items.sort_order')
            ->orderBy('menu_items.name')
            ->get([
                'menu_items.public_id',
                'menu_items.name',
                'menu_items.description',
                'menu_items.price',
                'menu_items.image_url',
                'menu_items.is_available',
                'menu_items.sort_order',
                'menu_categories.id as category_id',
                'menu_categories.name as category_name',
                'menu_categories.slug as category_slug',
            ])
            ->map(fn (object $row): array => [
                'public_id' => $row->public_id,
                'name' => $row->name,
                'description' => $row->description,
                'price' => (int) $row->price,
                'image_url' => $row->image_url,
                'is_available' => (bool) $row->is_available,
                'sort_order' => (int) $row->sort_order,
                'category' => [
                    'id' => (int) $row->category_id,
                    'name' => $row->category_name,
                    'slug' => $row->category_slug,
                ],
            ])->values()->all();

        return $this->success($items);
    }

    public function store(Request $request, string $cafePublicId): JsonResponse
    {
        $cafe = $this->getOwnerCafe($request, $cafePublicId);
        if (!$cafe) return $this->error('NOT_FOUND', 'Cafe tidak ditemukan atau bukan milik Anda.', 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'integer', 'exists:menu_categories,id'],
            'price' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:300'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'is_available' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ]);

        $publicId = (string) Str::uuid();

        DB::transaction(function () use ($data, $cafe, $publicId, $request): void {
            $itemId = DB::table('menu_items')->insertGetId([
                'public_id' => $publicId,
                'cafe_id' => $cafe->id,
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'image_url' => $data['image_url'] ?? null,
                'is_available' => $data['is_available'] ?? true,
                'is_active' => true,
                'sort_order' => $data['sort_order'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->audit($request, 'menu.create', $itemId, $data);
        });

        return $this->success($this->getItemResponse($publicId), 201);
    }

    public function update(Request $request, string $itemPublicId): JsonResponse
    {
        $item = $this->getOwnerMenuItem($request, $itemPublicId);
        if (!$item) return $this->error('NOT_FOUND', 'Menu tidak ditemukan atau bukan milik Anda.', 404);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:150'],
            'category_id' => ['sometimes', 'integer', 'exists:menu_categories,id'],
            'price' => ['sometimes', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:300'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'is_available' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ]);

        if (empty($data)) return $this->error('VALIDATION_ERROR', 'Tidak ada data yang dikirim untuk diubah.', 422);

        DB::transaction(function () use ($data, $item, $request): void {
            $updateData = $data + ['updated_at' => now()];
            DB::table('menu_items')->where('id', $item->id)->update($updateData);
            $this->audit($request, 'menu.update', (int) $item->id, $data);
        });

        return $this->success($this->getItemResponse($itemPublicId));
    }

    public function destroy(Request $request, string $itemPublicId): JsonResponse
    {
        $item = $this->getOwnerMenuItem($request, $itemPublicId);
        if (!$item) return $this->error('NOT_FOUND', 'Menu tidak ditemukan atau bukan milik Anda.', 404);

        DB::transaction(function () use ($item, $request): void {
            // Soft delete sesuai API_RULES: DELETE = is_active=0
            DB::table('menu_items')->where('id', $item->id)->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);
            $this->audit($request, 'menu.deactivate', (int) $item->id, ['is_active' => false]);
        });

        return $this->success(['public_id' => $itemPublicId, 'is_active' => false]);
    }

    public function setAvailability(Request $request, string $itemPublicId): JsonResponse
    {
        $item = $this->getOwnerMenuItem($request, $itemPublicId);
        if (!$item) return $this->error('NOT_FOUND', 'Menu tidak ditemukan atau bukan milik Anda.', 404);

        $data = $request->validate([
            'is_available' => ['required', 'boolean'],
        ]);

        DB::transaction(function () use ($data, $item, $request): void {
            DB::table('menu_items')->where('id', $item->id)->update([
                'is_available' => $data['is_available'],
                'updated_at' => now(),
            ]);
            $this->audit($request, 'menu.availability', (int) $item->id, $data);
        });

        return $this->success([
            'public_id' => $itemPublicId,
            'is_available' => (bool) $data['is_available'],
        ]);
    }

    private function getOwnerCafe(Request $request, string $cafePublicId): ?object
    {
        return DB::table('cafes')
            ->where('public_id', $cafePublicId)
            ->where('owner_id', $request->user()->id)
            ->whereNull('deleted_at')
            ->first();
    }

    private function getOwnerMenuItem(Request $request, string $itemPublicId): ?object
    {
        return DB::table('menu_items')
            ->join('cafes', 'menu_items.cafe_id', '=', 'cafes.id')
            ->where('menu_items.public_id', $itemPublicId)
            ->where('menu_items.is_active', true)
            ->where('cafes.owner_id', $request->user()->id)
            ->whereNull('cafes.deleted_at')
            ->select('menu_items.*')
            ->first();
    }

    private function getItemResponse(string $publicId): array
    {
        $row = DB::table('menu_items')
            ->join('menu_categories', 'menu_items.category_id', '=', 'menu_categories.id')
            ->where('menu_items.public_id', $publicId)
            ->first([
                'menu_items.public_id',
                'menu_items.name',
                'menu_items.description',
                'menu_items.price',
                'menu_items.image_url',
                'menu_items.is_available',
                'menu_items.is_active',
                'menu_items.sort_order',
                'menu_categories.id as category_id',
                'menu_categories.name as category_name',
                'menu_categories.slug as category_slug',
            ]);

        return [
            'public_id' => $row->public_id,
            'name' => $row->name,
            'description' => $row->description,
            'price' => (int) $row->price,
            'image_url' => $row->image_url,
            'is_available' => (bool) $row->is_available,
            'is_active' => (bool) $row->is_active,
            'sort_order' => (int) $row->sort_order,
            'category' => [
                'id' => (int) $row->category_id,
                'name' => $row->category_name,
                'slug' => $row->category_slug,
            ],
        ];
    }

    private function audit(Request $request, string $action, int $id, array $metadata): void
    {
        DB::table('audit_logs')->insert([
            'actor_user_id' => $request->user()->id,
            'actor_role' => $request->user()->role,
            'action' => $action,
            'entity_type' => 'menu_item',
            'entity_id' => (string) $id,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'metadata' => json_encode($metadata, JSON_THROW_ON_ERROR),
            'created_at' => now(),
        ]);
    }

    private function success(mixed $data, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'request_id' => (string) Str::uuid(),
        ], $status);
    }

    private function error(string $code, string $message, int $status): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => ['code' => $code, 'message' => $message, 'details' => []],
            'request_id' => (string) Str::uuid(),
        ], $status);
    }
}