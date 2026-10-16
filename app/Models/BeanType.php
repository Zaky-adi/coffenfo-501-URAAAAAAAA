<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BeanType extends Model
{
    use HasFactory;

    protected $table = 'bean_types';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function cafes(): BelongsToMany
    {
        return $this->belongsToMany(Cafe::class, 'cafe_beans');
    }
}
