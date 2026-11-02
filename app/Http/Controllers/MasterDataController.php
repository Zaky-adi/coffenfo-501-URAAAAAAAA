<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MasterDataController extends Controller
{
    private const TABLES = [
        'areas' => ['table' => 'areas', 'name_max' => 80, 'slug_max' => 80, 'geo' => true, 'icon' => false, 'ordered' => true],
        'beans' => ['table' => 'bean_types', 'name_max' => 255, 'slug_max' => 255, 'description' => true, 'icon' => false, 'ordered' => true],
        'species' => ['table' => 'coffee_species', 'name_max' => 60, 'slug_max' => 60, 'icon' => false, 'ordered' => true],
        'roast-levels' => ['table' => 'roast_levels', 'name_max' => 60, 'slug_max' => 60, 'icon' => false, 'ordered' => true],
        'brew-methods' => ['table' => 'brew_methods', 'name_max' => 60, 'slug_max' => 60, 'icon' => false, 'ordered' => true],
        'facilities' => ['table' => 'facilities', 'name_max' => 255, 'slug_max' => 255, 'icon' => true, 'ordered' => true],
        'menu-categories' => ['table' => 'menu_categories', 'name_max' => 255, 'slug_max' => 255, 'icon' => true, 'ordered' => true],
    ];

    public function index(string $type): JsonResponse
    {
        $config = $this->config($type);
        $query = DB::table($config['table'])->where('is_active', true);
        if (!empty($config['ordered'])) {
            $query->orderBy('sort_order');
        }
        $rows = $query->orderBy('name')->get();
        $data = $rows->map(function (object $row) use ($config): array {
            $item = ['id' => $row->id, 'name' => $row->name, 'slug' => $row->slug];
            if ($config['table'] === 'areas') {
                $item += ['latitude' => $row->latitude, 'longitude' => $row->longitude];
            }
            if (!empty($config['description'])) {
                $item['description'] = $row->description;
            }
            if (!empty($config['icon'])) {
                $item['icon'] = $row->icon;
            }
            if (!empty($config['ordered'])) {
                $item['sort_order'] = (int) $row->sort_order;
            }
            return $item;
        })->all();

        $etag = '"' . hash('sha256', json_encode($data, JSON_THROW_ON_ERROR)) . '"';
        if (request()->header('If-None-Match') === $etag) {
            return response()->json(null, 304)->header('ETag', $etag)->header('Cache-Control', 'public, max-age=3600');
        }
        return response()->json(['success' => true, 'data' => $data, 'request_id' => (string) Str::uuid()])
            ->header('ETag', $etag)->header('Cache-Control', 'public, max-age=3600');
    }

    public function store(Request $request, string $type): JsonResponse
    {
        return $this->save($request, $type);
    }

    public function update(Request $request, string $type, int $id): JsonResponse
    {
        return $this->save($request, $type, $id);
    }

    private function save(Request $request, string $type, ?int $id = null): JsonResponse
    {
        $config = $this->config($type);
        $table = $config['table'];
        $existing = $id === null ? null : DB::table($table)->where('id', $id)->first();
        if ($id !== null && !$existing) {
            return $this->error('NOT_FOUND', 'Data master tidak ditemukan.', 404);
        }

        $rules = [
            'name' => [$id === null ? 'required' : 'sometimes', 'string', 'min:1', 'max:' . $config['name_max']],
            'slug' => [$id === null ? 'required' : 'sometimes', 'string', 'min:1', 'max:' . $config['slug_max'], 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique($table, 'slug')->ignore($id)],
            'is_active' => ['sometimes', 'boolean'],
        ];
        if (!empty($config['ordered'])) {
            $rules['sort_order'] = ['sometimes', 'integer', 'min:0', 'max:4294967295'];
        }
        if (!empty($config['icon'])) {
            $rules['icon'] = ['sometimes', 'nullable', 'string', 'max:100'];
        }
        if (!empty($config['description'])) {
            $rules['description'] = ['sometimes', 'nullable', 'string', 'max:255'];
        }
        if (!empty($config['geo'])) {
            $rules['latitude'] = ['sometimes', 'nullable', 'numeric', 'between:-90,90'];
            $rules['longitude'] = ['sometimes', 'nullable', 'numeric', 'between:-180,180'];
        }
        $unknownFields = array_diff(array_keys($request->all()), array_keys($rules));
        if ($unknownFields !== []) {
            return $this->error('VALIDATION_ERROR', 'Data tidak valid.', 422);
        }
        $data = $request->validate($rules);
        $created = $id === null;
        if ($id === null) {
            $data['is_active'] ??= true;
            if (!empty($config['ordered'])) {
                $data['sort_order'] ??= 0;
            }
            $data['created_at'] = now();
        }
        $data['updated_at'] = now();
        $row = DB::transaction(function () use ($table, $id, $data, $request, $type, $created): object {
            if ($created) {
                $id = DB::table($table)->insertGetId($data);
            } else {
                DB::table($table)->where('id', $id)->update($data);
            }
            $this->audit($request, $created ? 'master.create' : 'master.update', $type, (int) $id, $data);
            return DB::table($table)->where('id', $id)->first();
        });
        return response()->json(['success' => true, 'data' => $row, 'request_id' => (string) Str::uuid()], $created ? 201 : 200);
    }

    private function config(string $type): array
    {
        if (!isset(self::TABLES[$type])) {
            throw ValidationException::withMessages(['type' => 'Tipe master tidak dikenal.']);
        }
        return self::TABLES[$type];
    }

    private function audit(Request $request, string $action, string $type, int $id, array $data): void
    {
        DB::table('audit_logs')->insert([
            'actor_user_id' => $request->user()->id,
            'actor_role' => $request->user()->role,
            'action' => $action,
            'entity_type' => $type,
            'entity_id' => (string) $id,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'metadata' => json_encode($data, JSON_THROW_ON_ERROR),
            'created_at' => now(),
        ]);
    }

    private function error(string $code, string $message, int $status): JsonResponse
    {
        return response()->json(['success' => false, 'error' => ['code' => $code, 'message' => $message, 'details' => []], 'request_id' => (string) Str::uuid()], $status);
    }
}
