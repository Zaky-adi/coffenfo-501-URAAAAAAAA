<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPrivacySetting extends Model
{
    use HasFactory;

    protected $table = 'user_privacy_settings';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'user_id',
        'personalization_enabled',
        'location_enabled',
        'consent_version',
        'consented_at',
    ];

    protected $casts = [
        'personalization_enabled' => 'boolean',
        'location_enabled' => 'boolean',
        'consented_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

