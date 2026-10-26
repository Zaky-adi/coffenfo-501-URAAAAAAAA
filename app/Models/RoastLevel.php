<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RoastLevel extends Model
{
    use HasFactory;

    protected $table = 'roast_levels';

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function cafes(): BelongsToMany
    {
        return $this->belongsToMany(Cafe::class, 'cafe_roast_levels', 'roast_level_id', 'cafe_id');
    }
}

