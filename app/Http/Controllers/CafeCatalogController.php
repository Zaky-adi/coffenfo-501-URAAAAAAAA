<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CafeCatalogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q' => ['sometimes', 'string', 'max:100'],
            'area_id' => ['sometimes', 'integer', 'exists:areas,id'],
            'price_range' => ['sometimes', 'array'],
            'price_range.*' => ['integer', 'between:1,3'],
            'bean_type_id' => ['sometimes', 'integer', 'exists:bean_types,id'],
            'roast_level_id' => ['sometimes', 'integer', 'exists:roast_levels,id'],
            'brew_method_id' => ['sometimes', 'integer', 'exists:brew_methods,id'],
            'facility_ids' => ['sometimes', 'array', 'max:20'],
            'facility_ids.*' => ['integer', 'exists:facilities,id'],
            'min_rating' => ['sometimes', 'numeric', 'between:0,5'],
            'self_roasting' => ['sometimes', 'boolean'],
            'open_now' => ['sometimes', 'boolean'],
            'sort' => ['sometimes', 'in:rating,newest,price_asc,price_desc,name'],
            'cursor' => ['sometimes', 'string', 'max:100'],
        ]);
        $query = DB::table('cafes')->where('status', 'active')->whereNull('deleted_at');
        $sort = $data['sort'] ?? 'rating';
        if (isset($data['q'])) {
            $term = '%' . addcslashes($data['q'], '%_\\') . '%';
            $query->where(function ($q) use ($term): void {
                $q->where('cafes.name', 'like', $term)->orWhere('cafes.tagline', 'like', $term)
                    ->orWhereExists(fn ($sub) => $sub->selectRaw('1')->from('menu_items')->whereColumn('menu_items.cafe_id', 'cafes.id')->where('menu_items.name', 'like', $term)->where('menu_items.is_active', true));
            });
            $this->recordSearch($request, $data['q']);
        }
        foreach (['area_id', 'has_self_roasting'] as $field) {
            $input = $field === 'has_self_roasting' ? 'self_roasting' : $field;
            if (array_key_exists($input, $data)) {
                $query->where($field, $data[$input]);
            }
        }
        if (isset($data['price_range'])) $query->whereIn('price_range_new', $data['price_range']);
        if (isset($data['min_rating'])) $query->where('avg_rating', '>=', $data['min_rating']);
        foreach ([['bean_type_id', 'cafe_beans', 'bean_type_id'], ['roast_level_id', 'cafe_roast_levels', 'roast_level_id'], ['brew_method_id', 'cafe_brew_methods', 'brew_method_id']] as [$input, $table, $column]) {
            if (isset($data[$input])) $query->whereExists(fn ($sub) => $sub->selectRaw('1')->from($table)->whereColumn($table . '.cafe_id', 'cafes.id')->where($table . '.' . $column, $data[$input]));
        }
        foreach ($data['facility_ids'] ?? [] as $facilityId) {
            $query->whereExists(fn ($sub) => $sub->selectRaw('1')->from('cafe_facilities')->whereColumn('cafe_facilities.cafe_id', 'cafes.id')->where('cafe_facilities.facility_id', $facilityId));
        }
        if (!empty($data['open_now'])) $this->whereOpenNow($query);
        if (!empty($data['cursor'])) {
            $cursor = $this->decodeCursor($data['cursor']);
            if (!$cursor || ($cursor['sort'] ?? null) !== $sort || !isset($cursor['id'], $cursor['primary'])) return $this->error('INVALID_CURSOR', 'Cursor tidak valid.', 400);
            $query->where(function ($q) use ($sort, $cursor): void {
                if ($sort === 'newest') { $q->where('cafes.id', '<', $cursor['id']); return; }
                if ($sort === 'rating') {
                    $q->where('avg_rating', '<', $cursor['primary'])
                        ->orWhere(function ($tie) use ($cursor): void { $tie->where('avg_rating', $cursor['primary'])->where('review_count', '<', $cursor['secondary']); })
                        ->orWhere(function ($tie) use ($cursor): void { $tie->where('avg_rating', $cursor['primary'])->where('review_count', $cursor['secondary'])->where('cafes.id', '>', $cursor['id']); });
                    return;
                }
                $column = match ($sort) { 'price_asc', 'price_desc' => 'price_range_new', 'name' => 'name', default => 'cafes.id' };
                $operator = $sort === 'price_desc' ? '<' : '>';
                $q->where($column, $operator, $cursor['primary'])
                    ->orWhere(function ($tie) use ($column, $operator, $cursor): void { $tie->where($column, $cursor['primary'])->where('cafes.id', '>', $cursor['id']); });
            });
        }
        $query = match ($sort) {
            'newest' => $query->orderByDesc('cafes.id'),
            'price_asc' => $query->orderBy('price_range_new')->orderBy('cafes.id'),
            'price_desc' => $query->orderByDesc('price_range_new')->orderBy('cafes.id'),
            'name' => $query->orderBy('name')->orderBy('cafes.id'),
            default => $query->orderByDesc('avg_rating')->orderByDesc('review_count')->orderBy('cafes.id'),
        };
        $rows = $query->limit(21)->get();
        return $this->listResponse($rows, 20, $sort);
    }

    public function nearby(Request $request): JsonResponse
    {
        $data = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'radius_km' => ['sometimes', 'numeric', 'gt:0', 'max:25'],
        ]);
        $radius = (float) ($data['radius_km'] ?? 10);
        $distance = '(6371 * ACOS(LEAST(1, GREATEST(-1, COS(RADIANS(?)) * COS(RADIANS(cafes.latitude)) * COS(RADIANS(cafes.longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(cafes.latitude))))))';
        $rows = DB::table('cafes')->select('cafes.*')->selectRaw($distance . ' AS distance_km', [$data['lat'], $data['lng'], $data['lat']])
            ->where('status', 'active')->whereNull('deleted_at')
            ->whereBetween('latitude', [$data['lat'] - ($radius / 110.574), $data['lat'] + ($radius / 110.574)])
            ->whereRaw($distance . ' <= ?', [$data['lat'], $data['lng'], $data['lat'], $radius])
            ->orderBy('distance_km')->limit(20)->get();
        return $this->listResponse($rows, 20);
    }

    public function popular(): JsonResponse
    {
        $rows = DB::table('cafes')->where('status', 'active')->whereNull('deleted_at')
            ->orderByRaw('((review_count / (review_count + 5)) * avg_rating) + ((5 / (review_count + 5)) * 4.0) DESC')
            ->limit(20)->get();
        return $this->listResponse($rows, 20);
    }

    public function featured(): JsonResponse
    {
        $rows = DB::table('featured_cafes')->join('cafes', 'cafes.id', '=', 'featured_cafes.cafe_id')
            ->whereDate('starts_on', '<=', now()->toDateString())->whereDate('ends_on', '>=', now()->toDateString())
            ->where('cafes.status', 'active')->whereNull('cafes.deleted_at')
            ->orderBy('featured_cafes.sort_order')->select('cafes.*', 'featured_cafes.label as featured_label')->limit(20)->get();
        return $this->listResponse($rows, 20);
    }

    public function show(string $id): JsonResponse
    {
        $cafe = DB::table('cafes')->where('public_id', $id)->where('status', 'active')->whereNull('deleted_at')->first();
        if (!$cafe) return $this->error('NOT_FOUND', 'Cafe tidak ditemukan.', 404);
        $data = $this->cafeItem($cafe);
        $data['photos'] = DB::table('cafe_photos')->where('cafe_id', $cafe->id)->orderByDesc('is_cover')->orderBy('id')->get(['path as url', 'caption', 'is_cover']);
        $data['opening_hours'] = DB::table('opening_hours')->where('cafe_id', $cafe->id)->orderBy('day')->get(['day', 'open_time', 'close_time', 'is_closed']);
        $today = now('Asia/Jakarta');
        $hours = DB::table('opening_hours')->where('cafe_id', $cafe->id)->where('day', $today->dayOfWeek)->first();
        $nowTime = $today->format('H:i:s');
        $isOpen = $hours && !$hours->is_closed && $hours->open_time && $hours->close_time
            && ($hours->close_time < $hours->open_time ? ($nowTime >= $hours->open_time || $nowTime <= $hours->close_time) : ($nowTime >= $hours->open_time && $nowTime <= $hours->close_time));
        $data['is_open_now'] = (bool) $isOpen;
        $data['open_until'] = $isOpen ? $hours->close_time : null;
        $data['beans'] = $this->relatedNames('cafe_beans', 'bean_types', 'bean_type_id', $cafe->id);
        $data['species'] = $this->relatedNames('cafe_species', 'coffee_species', 'species_id', $cafe->id);
        $data['roast_levels'] = $this->relatedNames('cafe_roast_levels', 'roast_levels', 'roast_level_id', $cafe->id);
        $data['brew_methods'] = $this->relatedNames('cafe_brew_methods', 'brew_methods', 'brew_method_id', $cafe->id);
        $data['serving_methods'] = $this->relatedNames('cafe_serving_methods', 'serving_methods', 'serving_method_id', $cafe->id);
        $data['facilities'] = $this->relatedNames('cafe_facilities', 'facilities', 'facility_id', $cafe->id);
        return $this->success($data);
    }

    public function similar(string $id): JsonResponse
    {
        $cafe = DB::table('cafes')->where('public_id', $id)->where('status', 'active')->whereNull('deleted_at')->first();
        if (!$cafe) return $this->error('NOT_FOUND', 'Cafe tidak ditemukan.', 404);
        $ids = DB::table('cafe_similarity')->where('cafe_id', $cafe->id)->orderByDesc('score')->limit(10)->pluck('similar_cafe_id');
        if ($ids->isEmpty()) {
            $ids = DB::table('cafes')->where('status', 'active')->whereNull('deleted_at')->where('id', '<>', $cafe->id)
                ->where('area_id', $cafe->area_id)->orderByRaw('ABS(price_range_new - ?) ASC', [$cafe->price_range_new])->limit(10)->pluck('id');
        }
        $rows = DB::table('cafes')->whereIn('id', $ids)->where('status', 'active')->whereNull('deleted_at')->get();
        return $this->listResponse($rows, 10);
    }

    public function testimonials(): JsonResponse
    {
        $rows = DB::table('reviews')->join('cafes', 'cafes.id', '=', 'reviews.cafe_id')->join('users', 'users.id', '=', 'reviews.user_id')
            ->where('reviews.is_featured', true)->whereIn('reviews.status', ['approved', 'reported'])->where('cafes.status', 'active')
            ->whereNull('cafes.deleted_at')->orderByDesc('reviews.created_at')->limit(12)
            ->get(['reviews.public_id', 'reviews.rating', 'reviews.comment', 'reviews.created_at', 'cafes.public_id as cafe_public_id', 'cafes.name as cafe_name', 'users.name as user_name']);
        return $this->success($rows->map(fn ($row) => [
            'public_id' => $row->public_id, 'rating' => (int) $row->rating, 'comment' => $row->comment,
            'created_at' => $row->created_at, 'cafe' => ['public_id' => $row->cafe_public_id, 'name' => $row->cafe_name],
            'user_name' => $row->user_name,
        ])->all());
    }

    private function recordSearch(Request $request, string $term): void
    {
        $user = $request->user();
        if ($user && mb_strlen(trim($term)) >= 2 && DB::table('user_privacy_settings')->where('user_id', $user->id)->value('personalization_enabled')) {
            DB::table('user_events')->insert(['user_id' => $user->id, 'event_type' => 'search', 'query_text' => mb_substr($term, 0, 100), 'created_at' => now()]);
        }
    }

    private function whereOpenNow($query): void
    {
        $now = now('Asia/Jakarta');
        $day = $now->dayOfWeek;
        $time = $now->format('H:i:s');
        $query->whereExists(function ($sub) use ($day, $time): void {
            $sub->selectRaw('1')->from('opening_hours')->whereColumn('opening_hours.cafe_id', 'cafes.id')->where('opening_hours.day', $day)->where('opening_hours.is_closed', false)
                ->whereRaw('( (opening_hours.close_time >= opening_hours.open_time AND ? BETWEEN opening_hours.open_time AND opening_hours.close_time) OR (opening_hours.close_time < opening_hours.open_time AND (? >= opening_hours.open_time OR ? <= opening_hours.close_time)) )', [$time, $time, $time]);
        });
    }

    private function relatedNames(string $junction, string $target, string $foreignKey, int $cafeId): array
    {
        return DB::table($junction)->join($target, $target . '.id', '=', $junction . '.' . $foreignKey)
            ->where($junction . '.cafe_id', $cafeId)->orderBy($target . '.name')->get([$target . '.id', $target . '.name', $target . '.slug'])->all();
    }

    private function listResponse($rows, int $limit, ?string $sort = null): JsonResponse
    {
        $hasMore = $rows->count() > $limit;
        $items = $rows->take($limit)->map(fn ($row) => $this->cafeItem($row))->all();
        $last = $rows->take($limit)->last();
        $nextCursor = null;
        if ($hasMore && $last) {
            $nextCursor = $sort ? $this->encodeCafeCursor($last, $sort) : $this->encodeCursor((int) $last->id);
        }
        return response()->json(['success' => true, 'data' => $items, 'meta' => ['limit' => $limit, 'has_more' => $hasMore, 'next_cursor' => $nextCursor], 'request_id' => (string) Str::uuid()]);
    }

    private function cafeItem(object $row): array
    {
        $item = ['public_id' => $row->public_id, 'name' => $row->name, 'slug' => $row->slug, 'tagline' => $row->tagline ?? null, 'address' => $row->address, 'area_id' => $row->area_id, 'price_range' => (int) ($row->price_range_new ?? 2), 'avg_rating' => (float) $row->avg_rating, 'review_count' => (int) $row->review_count, 'cover_photo' => $row->cover_photo, 'latitude' => (float) $row->latitude, 'longitude' => (float) $row->longitude];
        if (isset($row->distance_km)) $item['distance_km'] = round((float) $row->distance_km, 2);
        if (isset($row->featured_label)) $item['featured_label'] = $row->featured_label;
        return $item;
    }

    private function encodeCursor(int $id): string { return rtrim(strtr(base64_encode((string) $id), '+/', '-_'), '='); }
    private function encodeCafeCursor(object $row, string $sort): string
    {
        $primary = match ($sort) { 'rating' => (float) $row->avg_rating, 'price_asc', 'price_desc' => (int) $row->price_range_new, 'name' => (string) $row->name, default => (int) $row->id };
        $payload = ['sort' => $sort, 'id' => (int) $row->id, 'primary' => $primary];
        if ($sort === 'rating') $payload['secondary'] = (int) $row->review_count;
        return rtrim(strtr(base64_encode(json_encode($payload, JSON_THROW_ON_ERROR)), '+/', '-_'), '=');
    }
    private function decodeCursor(string $cursor): ?array
    {
        $raw = base64_decode(strtr($cursor, '-_', '+/'), true);
        if ($raw === false) return null;
        $payload = json_decode($raw, true);
        return is_array($payload) ? $payload : null;
    }
    private function success(mixed $data): JsonResponse { return response()->json(['success' => true, 'data' => $data, 'request_id' => (string) Str::uuid()]); }
    private function error(string $code, string $message, int $status): JsonResponse { return response()->json(['success' => false, 'error' => ['code' => $code, 'message' => $message, 'details' => []], 'request_id' => (string) Str::uuid()], $status); }
}
