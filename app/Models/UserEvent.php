<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserEvent extends Model
{
    use HasFactory;

    protected $table = 'user_events';
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'event_type',
        'cafe_id',
        'query_text',
        'surface',
        'position',
        'rec_request_id',
        'client_event_id',
        'client_ts',
        'model_version',
        'value_num',
    ];

    protected $casts = [
        'position' => 'integer',
        'client_ts' => 'datetime',
        'value_num' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cafe(): BelongsTo
    {
        return $this->belongsTo(Cafe::class);
    }
}

