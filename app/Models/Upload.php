<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Upload extends Model
{
    use HasFactory;

    protected $table = 'uploads';
    const UPDATED_AT = null;

    protected $fillable = [
        'public_id',
        'user_id',
        'purpose',
        'object_key',
        'content_type',
        'declared_size',
        'size_bytes',
        'width',
        'height',
        'status',
        'rejection_reason',
        'url',
        'consumed_at',
        'expires_at',
        'confirmed_at',
    ];

    protected $casts = [
        'declared_size' => 'integer',
        'size_bytes' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'consumed_at' => 'datetime',
        'expires_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($upload) {
            if (empty($upload->public_id)) {
                $upload->public_id = Str::uuid()->toString();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

