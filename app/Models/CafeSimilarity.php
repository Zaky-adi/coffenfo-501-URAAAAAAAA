<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CafeSimilarity extends Model
{
    use HasFactory;

    protected $table = 'cafe_similarity';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'cafe_id',
        'similar_cafe_id',
        'score',
        'method',
        'computed_at',
    ];

    protected $casts = [
        'score' => 'decimal:5',
        'computed_at' => 'datetime',
    ];

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class, 'cafe_id');
    }

    public function similarCafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class, 'similar_cafe_id');
    }
}

