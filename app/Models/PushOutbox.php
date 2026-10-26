<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PushOutbox extends Model
{
    use HasFactory;

    protected $table = 'push_outbox';
    const UPDATED_AT = null;

    protected $fillable = [
        'notification_id',
        'user_device_id',
        'status',
        'attempts',
        'last_error',
        'scheduled_at',
        'sent_at',
    ];

    protected $casts = [
        'attempts' => 'integer',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class);
    }

    public function userDevice(): BelongsTo
    {
        return $this->belongsTo(UserDevice::class, 'user_device_id');
    }
}

