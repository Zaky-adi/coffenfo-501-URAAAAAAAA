<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CafeManagementController extends Controller
{
    public function adminIndex(Request $request): JsonResponse
    {
        $data = $request->validate(['status' => ['sometimes', 'in:draft,pending,active,suspended'], 'q' => ['sometimes', 'string', 'max:100'], 'cursor' => ['sometimes', 'string', 'max:80']]);
        $query = DB::table('cafes')->whereNull('deleted_at')->orderByDesc('id');
        if (isset($data['status'])) $query->where('status', $data['status']);
        if (isset($data['q'])) $query->where('name', 'like', '%' . addcslashes($data['q'], '%_\\') . '%');
        if (isset($data['cursor'])) {
            $cursor = $this->decodeCursor($data['cursor']);
            if ($cursor === null) return $this->error('INVALID_CURSOR', 'Cursor tidak valid.', 400);
            $query->where('id', '<', $cursor);
        }
        $rows = $query->limit(51)->get();
        return $this->paginate($rows, 50);
    }

    public function ownerIndex(Request $request): JsonResponse
    {
        return $this->paginate(DB::table('cafes')->where('owner_id', $request->user()->id)->whereNull('deleted_at')->orderByDesc('id')->limit(51)->get(), 50);
    }

    public function adminShow(string $publicId): JsonResponse
    {
        $cafe = DB::table('cafes')->where('public_id', $publicId)->whereNull('deleted_at')->first();
        if (!$cafe) return $this->error('NOT_FOUND', 'Cafe tidak ditemukan.', 404);
        $data = $this->detailByInternalId((int) $cafe->id);
        $data['opening_hours'] = DB::table('opening_hours')->where('cafe_id', $cafe->id)->orderBy('day')->get(['day', 'open_time', 'close_time', 'is_closed']);
        $data['photos'] = DB::table('cafe_photos')->where('cafe_id', $cafe->id)->orderByDesc('is_cover')->orderBy('id')->get(['path as url', 'caption', 'is_cover']);
        return $this->success($data);
    }

    public function create(Request $request, bool $admin = false): JsonResponse
    {
        $data = $this->validateCafe($request, true, $admin);
        $id = DB::transaction(function () use ($data, $request, $admin): int {
            $cafeId = DB::table('cafes')->insertGetId($this->cafeColumns($data, $request, $admin));
            $this->syncRelations($cafeId, $data, $request, $admin);
            $this->audit($request, $admin ? 'cafe.create' : 'cafe.owner_create', $cafeId, $data);
            return $cafeId;
        });
        return $this->success($this->detailByInternalId($id), 201);
    }

    public function ownerCreate(Request $request): JsonResponse { return $this->create($request, false); }
    public function adminCreate(Request $request): JsonResponse { return $this->create($request, true); }
    public function ownerUpdate(Request $request, string $publicId): JsonResponse { return $this->update($request, $publicId, false); }
    public function adminEdit(Request $request, string $publicId): JsonResponse { return $this->update($request, $publicId, true); }

    public function update(Request $request, string $publicId, bool $admin = false): JsonResponse
    {
        $query = DB::table('cafes')->where('public_id', $publicId)->whereNull('deleted_at');
        if (!$admin) $query->where('owner_id', $request->user()->id);
        $cafe = $query->first();
        if (!$cafe) return $this->error('NOT_FOUND', 'Cafe tidak ditemukan.', 404);
        $data = $this->validateCafe($request, false, $admin);
        DB::transaction(function () use ($request, $data, $admin, $cafe): void {
            $changes = $this->cafeColumns($data, $request, $admin, false);
            if ($changes !== []) DB::table('cafes')->where('id', $cafe->id)->update($changes);
            $this->syncRelations((int) $cafe->id, $data, $request, $admin);
            $this->audit($request, $admin ? 'cafe.update' : 'cafe.owner_update', (int) $cafe->id, $data);
        });
        return $this->success($this->detailByInternalId((int) $cafe->id));
    }

    public function setAcceptsOrders(Request $request, string $publicId): JsonResponse
    {
        $data = $request->validate(['accepts_orders' => ['required', 'boolean']]);
        $cafe = DB::table('cafes')->where('public_id', $publicId)->where('owner_id', $request->user()->id)->where('status', 'active')->whereNull('deleted_at')->first();
        if (!$cafe) return $this->error('NOT_FOUND', 'Cafe tidak ditemukan.', 404);
        DB::table('cafes')->where('id', $cafe->id)->update(['accepts_orders' => $data['accepts_orders'], 'updated_at' => now()]);
        return $this->success(['public_id' => $publicId, 'accepts_orders' => (bool) $data['accepts_orders']]);
    }

    public function adminUpdate(Request $request, string $publicId): JsonResponse
    {
        $data = $request->validate(['status' => ['sometimes', 'in:draft,pending,active,suspended'], 'is_active' => ['sometimes', 'boolean']]);
        $cafe = DB::table('cafes')->where('public_id', $publicId)->whereNull('deleted_at')->first();
        if (!$cafe) return $this->error('NOT_FOUND', 'Cafe tidak ditemukan.', 404);
        $data['is_active'] = isset($data['status']) ? $data['status'] === 'active' : ($data['is_active'] ?? (bool) $cafe->is_active);
        DB::transaction(function () use ($request, $data, $cafe): void {
            DB::table('cafes')->where('id', $cafe->id)->update($data + ['updated_at' => now()]);
            $this->audit($request, 'cafe.status_update', (int) $cafe->id, $data);
        });
        return $this->success($this->detailByInternalId((int) $cafe->id));
    }

    public function setFeatured(Request $request, int $id): JsonResponse
    {
        $data = $request->validate(['cafe_id' => ['required', 'uuid', 'exists:cafes,public_id'], 'label' => ['sometimes', 'nullable', 'string', 'max:60'], 'starts_on' => ['required', 'date'], 'ends_on' => ['required', 'date', 'after_or_equal:starts_on'], 'sort_order' => ['sometimes', 'integer', 'between:0,255']]);
        $cafeId = DB::table('cafes')->where('public_id', $data['cafe_id'])->value('id');
        $featured = DB::table('featured_cafes')->where('id', $id)->first();
        $payload = ['cafe_id' => $cafeId, 'label' => $data['label'] ?? null, 'starts_on' => $data['starts_on'], 'ends_on' => $data['ends_on'], 'sort_order' => $data['sort_order'] ?? 0, 'created_by' => $request->user()->id, 'updated_at' => now()];
        if ($featured) DB::table('featured_cafes')->where('id', $id)->update($payload);
        else $id = DB::table('featured_cafes')->insertGetId($payload + ['created_at' => now()]);
        $this->audit($request, 'cafe.featured_update', $id, $payload);
        return $this->success(['id' => $id], $featured ? 200 : 201);
    }

    public function deleteFeatured(Request $request, int $id): JsonResponse
    {
        $entry = DB::table('featured_cafes')->where('id', $id)->first();
        if (!$entry) return $this->error('NOT_FOUND', 'Cafe pilihan tidak ditemukan.', 404);
        DB::table('featured_cafes')->where('id', $id)->delete();
        $this->audit($request, 'cafe.featured_delete', $id, []);
        return response()->json(['success' => true, 'data' => ['message' => 'Cafe pilihan dihapus.'], 'request_id' => (string) Str::uuid()]);
    }

    private function validateCafe(Request $request, bool $creating, bool $admin): array
    {
        $rules = [
            'name' => [$creating ? 'required' : 'sometimes', 'string', 'min:2', 'max:150'],
            'slug' => ['sometimes', 'string', 'max:160', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', \Illuminate\Validation\Rule::unique('cafes', 'slug')->ignore($request->route('publicId') ? DB::table('cafes')->where('public_id', $request->route('publicId'))->value('id') : null)],
            'tagline' => ['sometimes', 'nullable', 'string', 'max:160'], 'description' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'coffee_note' => ['sometimes', 'nullable', 'string', 'max:2000'], 'address' => [$creating ? 'required' : 'sometimes', 'string', 'max:255'],
            'area_id' => [$creating ? 'required' : 'sometimes', 'integer', Rule::exists('areas', 'id')->where('is_active', true)],
            'latitude' => [$creating ? 'required' : 'sometimes', 'numeric', 'between:-90,90'], 'longitude' => [$creating ? 'required' : 'sometimes', 'numeric', 'between:-180,180'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'], 'price_range_new' => ['sometimes', 'integer', 'between:1,3'],
            'has_self_roasting' => ['sometimes', 'boolean'], 'accepts_orders' => ['sometimes', 'boolean'],
            'bean_type_ids' => ['sometimes', 'array'], 'bean_type_ids.*' => ['distinct', 'integer', 'exists:bean_types,id'],
            'species_ids' => ['sometimes', 'array'], 'species_ids.*' => ['distinct', 'integer', 'exists:coffee_species,id'],
            'roast_level_ids' => ['sometimes', 'array'], 'roast_level_ids.*' => ['distinct', 'integer', 'exists:roast_levels,id'],
            'brew_method_ids' => ['sometimes', 'array'], 'brew_method_ids.*' => ['distinct', 'integer', 'exists:brew_methods,id'],
            'serving_method_ids' => ['sometimes', 'array'], 'serving_method_ids.*' => ['distinct', 'integer', 'exists:serving_methods,id'],
            'facility_ids' => ['sometimes', 'array'], 'facility_ids.*' => ['distinct', 'integer', 'exists:facilities,id'],
            'photo_upload_ids' => ['sometimes', 'array', 'max:10'], 'photo_upload_ids.*' => ['distinct', 'uuid'],
            'cover_upload_id' => ['sometimes', 'nullable', 'uuid'],
            'opening_hours' => ['sometimes', 'array', 'max:7'],
            'opening_hours.*.day' => ['required_with:opening_hours', 'distinct', 'integer', 'between:0,6'],
            'opening_hours.*.open_time' => ['nullable', 'date_format:H:i'], 'opening_hours.*.close_time' => ['nullable', 'date_format:H:i'],
            'opening_hours.*.is_closed' => ['required_with:opening_hours', 'boolean'],
            'status' => [$admin ? 'sometimes' : 'prohibited', 'in:draft,pending,active,suspended'],
        ];
        $allowed = array_keys($rules);
        $unknown = array_diff(array_keys($request->all()), $allowed);
        if ($unknown !== []) abort(response()->json(['success' => false, 'error' => ['code' => 'VALIDATION_ERROR', 'message' => 'Data tidak valid.', 'details' => []], 'request_id' => (string) Str::uuid()], 422));
        $data = $request->validate($rules);
        if (isset($data['cover_upload_id']) && $data['cover_upload_id'] !== null
            && !in_array($data['cover_upload_id'], $data['photo_upload_ids'] ?? [], true)) {
            abort(response()->json(['success' => false, 'error' => ['code' => 'VALIDATION_ERROR', 'message' => 'Foto sampul harus termasuk dalam photo_upload_ids.', 'details' => []], 'request_id' => (string) Str::uuid()], 422));
        }
        return $data;
    }

    private function cafeColumns(array $data, Request $request, bool $admin, bool $creating = true): array
    {
        $fields = ['name', 'slug', 'tagline', 'description', 'coffee_note', 'address', 'area_id', 'latitude', 'longitude', 'phone', 'price_range_new', 'has_self_roasting', 'accepts_orders'];
        $columns = array_intersect_key($data, array_flip($fields));
        if ($admin && isset($data['status'])) {
            $columns['status'] = $data['status'];
            $columns['is_active'] = $data['status'] === 'active';
        }
        if ($creating) {
            $columns += ['public_id' => (string) Str::uuid(), 'owner_id' => $admin ? null : $request->user()->id, 'city' => 'Batam', 'price_range' => 'mid', 'status' => $admin ? ($data['status'] ?? 'active') : 'pending', 'is_active' => $admin && ($data['status'] ?? 'active') === 'active', 'avg_rating' => 0, 'review_count' => 0, 'created_at' => now()];
            $columns['slug'] ??= Str::slug($data['name']) . '-' . Str::lower(Str::random(6));
        }
        $columns['updated_at'] = now();
        return $columns;
    }

    private function syncRelations(int $cafeId, array $data, Request $request, bool $admin): void
    {
        $relations = [
            'bean_type_ids' => ['cafe_beans', 'bean_type_id'], 'species_ids' => ['cafe_species', 'species_id'],
            'roast_level_ids' => ['cafe_roast_levels', 'roast_level_id'], 'brew_method_ids' => ['cafe_brew_methods', 'brew_method_id'],
            'serving_method_ids' => ['cafe_serving_methods', 'serving_method_id'], 'facility_ids' => ['cafe_facilities', 'facility_id'],
        ];
        foreach ($relations as $input => [$table, $column]) {
            if (array_key_exists($input, $data)) {
                DB::table($table)->where('cafe_id', $cafeId)->delete();
                foreach ($data[$input] as $id) DB::table($table)->insert(['cafe_id' => $cafeId, $column => $id]);
            }
        }
        if (array_key_exists('opening_hours', $data)) {
            DB::table('opening_hours')->where('cafe_id', $cafeId)->delete();
            foreach ($data['opening_hours'] as $hours) DB::table('opening_hours')->insert($hours + ['cafe_id' => $cafeId, 'created_at' => now(), 'updated_at' => now()]);
        }
        if (array_key_exists('photo_upload_ids', $data)) {
            DB::table('cafe_photos')->where('cafe_id', $cafeId)->delete();
            $coverId = $data['cover_upload_id'] ?? ($data['photo_upload_ids'][0] ?? null);
            if ($data['photo_upload_ids'] === []) DB::table('cafes')->where('id', $cafeId)->update(['cover_photo' => null]);
            foreach ($data['photo_upload_ids'] as $uploadId) {
                $uploadQuery = DB::table('uploads')->where('public_id', $uploadId)->where('purpose', 'cafe_photo')->where('status', 'ready')->whereNull('consumed_at');
                if (!$admin) $uploadQuery->where('user_id', $request->user()->id);
                $upload = $uploadQuery->first();
                if (!$upload) abort(response()->json(['success' => false, 'error' => ['code' => 'NOT_FOUND', 'message' => 'Unggahan foto cafe tidak ditemukan atau tidak dapat digunakan.', 'details' => []], 'request_id' => (string) Str::uuid()], 404));
                DB::table('cafe_photos')->insert(['cafe_id' => $cafeId, 'path' => $upload->url, 'is_cover' => $uploadId === $coverId, 'created_at' => now(), 'updated_at' => now()]);
                DB::table('uploads')->where('id', $upload->id)->update(['consumed_at' => now()]);
                if ($uploadId === $coverId) DB::table('cafes')->where('id', $cafeId)->update(['cover_photo' => $upload->url]);
            }
        }
    }

    private function detailByInternalId(int $id): array
    {
        $row = DB::table('cafes')->where('id', $id)->first();
        return [
            'public_id' => $row->public_id, 'name' => $row->name, 'slug' => $row->slug,
            'tagline' => $row->tagline, 'description' => $row->description, 'coffee_note' => $row->coffee_note,
            'status' => $row->status, 'is_active' => (bool) $row->is_active,
            'address' => $row->address, 'area_id' => $row->area_id, 'latitude' => (float) $row->latitude,
            'longitude' => (float) $row->longitude, 'phone' => $row->phone,
            'price_range' => (int) $row->price_range_new, 'has_self_roasting' => (bool) $row->has_self_roasting,
            'accepts_orders' => (bool) $row->accepts_orders, 'avg_rating' => (float) $row->avg_rating,
            'review_count' => (int) $row->review_count, 'cover_photo' => $row->cover_photo,
            'owner_public_id' => $row->owner_id ? DB::table('users')->where('id', $row->owner_id)->value('public_id') : null,
        ];
    }
    private function audit(Request $request, string $action, int $id, array $metadata): void
    {
        DB::table('audit_logs')->insert(['actor_user_id' => $request->user()->id, 'actor_role' => $request->user()->role, 'action' => $action, 'entity_type' => 'cafe', 'entity_id' => (string) $id, 'ip_address' => $request->ip(), 'user_agent' => substr((string) $request->userAgent(), 0, 255), 'metadata' => json_encode($metadata, JSON_THROW_ON_ERROR), 'created_at' => now()]);
    }
    private function paginate($rows, int $limit): JsonResponse
    {
        $more = $rows->count() > $limit; $items = $rows->take($limit)->values();
        $nextId = $more ? $items->last()->id : null;
        $data = $items->map(fn ($row) => ['public_id' => $row->public_id, 'name' => $row->name, 'slug' => $row->slug, 'tagline' => $row->tagline, 'status' => $row->status, 'address' => $row->address, 'area_id' => $row->area_id, 'owner_public_id' => $row->owner_id ? DB::table('users')->where('id', $row->owner_id)->value('public_id') : null])->all();
        return $this->success(['items' => $data, 'limit' => $limit, 'has_more' => $more, 'next_cursor' => $nextId ? $this->encodeCursor((int) $nextId) : null]);
    }
    private function encodeCursor(int $id): string { return rtrim(strtr(base64_encode((string) $id), '+/', '-_'), '='); }
    private function decodeCursor(string $cursor): ?int { $raw = base64_decode(strtr($cursor, '-_', '+/'), true); return $raw !== false && ctype_digit($raw) ? (int) $raw : null; }
    private function success(mixed $data, int $status = 200): JsonResponse { return response()->json(['success' => true, 'data' => $data, 'request_id' => (string) Str::uuid()], $status); }
    private function error(string $code, string $message, int $status): JsonResponse { return response()->json(['success' => false, 'error' => ['code' => $code, 'message' => $message, 'details' => []], 'request_id' => (string) Str::uuid()], $status); }
}
