<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'public_id',
        'order_code',
        'user_id',
        'cafe_id',
        'order_type',
        'table_number',
        'customer_note',
        'status',
        'subtotal',
        'service_fee',
        'discount_amount',
        'total_amount',
        'voucher_redemption_id',
        'est_prep_min',
        'est_prep_max',
        'cancel_reason',
        'paid_at',
        'accepted_at',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'service_fee' => 'integer',
        'discount_amount' => 'integer',
        'total_amount' => 'integer',
        'est_prep_min' => 'integer',
        'est_prep_max' => 'integer',
        'paid_at' => 'datetime',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->public_id)) {
                $order->public_id = Str::uuid()->toString();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }

    public function voucherRedemption(): BelongsTo
    {
        return $this->belongsTo(VoucherRedemption::class, 'voucher_redemption_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}

