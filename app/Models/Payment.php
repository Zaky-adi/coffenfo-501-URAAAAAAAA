<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'public_id',
        'order_id',
        'method',
        'channel',
        'provider',
        'provider_ref',
        'amount',
        'status',
        'expires_at',
        'paid_at',
        'callback_payload',
    ];

    protected $casts = [
        'amount' => 'integer',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
        'callback_payload' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($payment) {
            if (empty($payment->public_id)) {
                $payment->public_id = Str::uuid()->toString();
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

