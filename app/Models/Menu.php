<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cafe_id',
        'menu_category_id',
        'name',
        'description',
        'price',
        'photo',
        'is_available',
        'meta',
    ];

    protected $casts = [
        'price' => 'integer',
        'is_available' => 'boolean',
        'meta' => 'array',
    ];

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    /** Menu tersedia saja. */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
}
