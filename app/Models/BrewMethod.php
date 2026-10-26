<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BrewMethod extends Model
{
    use HasFactory;

    protected $table = 'brew_methods';

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
        return $this->belongsToMany(Cafe::class, 'cafe_brew_methods', 'brew_method_id', 'cafe_id');
    }
}

