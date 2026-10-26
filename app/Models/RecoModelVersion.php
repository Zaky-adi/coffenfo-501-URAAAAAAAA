<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoModelVersion extends Model
{
    use HasFactory;

    protected $table = 'reco_model_versions';
    const UPDATED_AT = null;

    protected $fillable = [
        'version',
        'description',
        'params',
        'traffic_percent',
        'is_active',
    ];

    protected $casts = [
        'params' => 'array',
        'traffic_percent' => 'integer',
        'is_active' => 'boolean',
    ];
}

