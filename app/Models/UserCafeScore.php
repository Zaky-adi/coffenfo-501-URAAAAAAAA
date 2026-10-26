<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCafeScore extends Model
{
    use HasFactory;

    protected $table = 'user_cafe_scores';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'cafe_id',
        'score',
        'reason_code',
        'reason_ref',
        'model_version',
        'computed_at',
    ];

    protected $casts = [
        'score' => 'decimal:5',
        'computed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }
}

