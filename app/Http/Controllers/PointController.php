<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{
    public function getBalance(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $row = DB::table('user_points')->where('user_id', $userId)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'balance' => $row ? (int) $row->balance : 0,
                'lifetime_earned' => $row ? (int) $row->lifetime_earned : 0,
                'updated_at' => $row ? $row->updated_at : null,
            ],
        ]);
    }

    public function getLedger(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $page = max((int) $request->query('page', 1), 1);
        $perPage = min(max((int) $request->query('per_page', 20), 1), 50);

        $query = DB::table('points_ledger')
            ->where('user_id', $userId)
            ->orderByDesc('id');

        $total = $query->count();
        $rows = $query->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        $items = $rows->map(function ($row) {
            return [
                'id' => (int) $row->id,
                'entry_type' => $row->entry_type,
                'points' => (int) $row->points,
                'balance_after' => (int) $row->balance_after,
                'source_type' => $row->source_type,
                'note' => $row->note,
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
}