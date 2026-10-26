<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserDevice extends Model
{
    use HasFactory;

    protected $table = 'user_devices';

    protected $fillable = [
        'user_id',
        'device_id',
        'platform',
        'device_name',
        'app_version',
        'locale',
        'push_token',
        'push_enabled',
        'last_seen_at',
    ];

    protected $casts = [
        'push_enabled' => 'boolean',
        'last_seen_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function outboxes(): HasMany
    {
        return $this->hasMany(PushOutbox::class, 'user_device_id');
    }
}

