<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointsLedger extends Model
{
    use HasFactory;

    protected $table = 'points_ledger';
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'entry_type',
        'points',
        'balance_after',
        'source_type',
        'source_id',
        'idempotency_key',
        'note',
    ];

    protected $casts = [
        'points' => 'integer',
        'balance_after' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

