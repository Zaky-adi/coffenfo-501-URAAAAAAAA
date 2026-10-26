<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPE_PERCENT = 'percent';
    public const TYPE_FIXED = 'fixed';

    protected $fillable = [
        'cafe_id',
        'title',
        'description',
        'points_cost',
        'discount_type',
        'discount_value',
        'min_purchase',
        'quota',
        'redeemed_count',
        'valid_from',
        'valid_until',
        'is_active',
        'terms',
    ];

    protected $casts = [
        'points_cost' => 'integer',
        'discount_value' => 'integer',
        'min_purchase' => 'integer',
        'quota' => 'integer',
        'redeemed_count' => 'integer',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(VoucherRedemption::class);
    }

    /** Kuota tersisa (null quota = tanpa batas). */
    public function remainingQuota(): ?int
    {
        return $this->quota === null ? null : max(0, $this->quota - $this->redeemed_count);
    }

    /** Masih berlaku secara waktu? */
    public function isCurrentlyValid(): bool
    {
        $today = now()->startOfDay();

        return $this->is_active
            && (! $this->valid_from || $today->gte($this->valid_from))
            && (! $this->valid_until || $today->lte($this->valid_until));
    }
}
