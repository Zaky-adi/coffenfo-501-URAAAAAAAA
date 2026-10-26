<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHiddenCafe extends Model
{
    use HasFactory;

    protected $table = 'user_hidden_cafes';
    protected $primaryKey = null;
    public $incrementing = false;
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'cafe_id',
        'reason',
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

