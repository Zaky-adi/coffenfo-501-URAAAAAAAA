<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTasteWeight extends Model
{
    use HasFactory;

    protected $table = 'user_taste_weights';
    protected $primaryKey = null;
    public $incrementing = false;
    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id',
        'feature_type',
        'feature_key',
        'weight',
        'evidence_count',
    ];

    protected $casts = [
        'weight' => 'decimal:4',
        'evidence_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

