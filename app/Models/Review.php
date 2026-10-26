<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'user_id',
        'cafe_id',
        'visit_id',
        'rating',
        'comment',
        'status',
        'rejection_reason',
        'moderated_by',
        'moderated_at',
        'has_photo',
        'helpful_count',
        'is_featured',
        'is_anonymous',
    ];

    protected $casts = [
        'rating' => 'integer',
        'helpful_count' => 'integer',
        'has_photo' => 'boolean',
        'is_featured' => 'boolean',
        'is_anonymous' => 'boolean',
        'moderated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ReviewPhoto::class);
    }

    public function reply(): HasOne
    {
        return $this->hasOne(ReviewReply::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ReviewReport::class);
    }
}
