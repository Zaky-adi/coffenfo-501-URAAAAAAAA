<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnershipRequest extends Model
{
    use HasFactory;

    protected $table = 'partnership_requests';

    protected $fillable = [
        'cafe_name',
        'owner_name',
        'whatsapp',
        'area_id',
        'status',
        'admin_note',
        'reviewed_by',
        'ip_address',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}

