<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PointTransaction extends Model
{
    use HasFactory;

    public const TYPE_REVIEW_BONUS = 'review_bonus';
    public const TYPE_REDEEM = 'redeem';
    public const TYPE_ADJUSTMENT = 'adjustment';

    protected $fillable = [
        'user_id',
        'points',
        'type',
        'reference_type',
        'reference_id',
        'balance_after',
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

    /** Record yang menjadi sumber poin (Review, VoucherRedemption, dll). */
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
