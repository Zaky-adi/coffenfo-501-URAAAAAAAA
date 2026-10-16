<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Cafe extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'address',
        'city',
        'latitude',
        'longitude',
        'phone',
        'price_range',
        'avg_rating',
        'review_count',
        'cover_photo',
        'is_active',
        'meta',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'avg_rating' => 'float',
        'review_count' => 'integer',
        'is_active' => 'boolean',
        'meta' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $cafe) {
            if (blank($cafe->slug)) {
                $cafe->slug = Str::slug($cafe->name).'-'.Str::lower(Str::random(6));
            }
        });
    }

    /* ------------------------------------------------------------------ */
    /* Relasi                                                             */
    /* ------------------------------------------------------------------ */

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(CafePhoto::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'cafe_facilities');
    }

    public function beanTypes(): BelongsToMany
    {
        return $this->belongsToMany(BeanType::class, 'cafe_beans');
    }

    public function servingMethods(): BelongsToMany
    {
        return $this->belongsToMany(ServingMethod::class, 'cafe_serving_methods');
    }

    public function openingHours(): HasMany
    {
        return $this->hasMany(OpeningHour::class);
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    /* ------------------------------------------------------------------ */
    /* Scope & Helper                                                     */
    /* ------------------------------------------------------------------ */

    /** Kafe aktif saja. */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Kafe dalam radius tertentu (km) dari titik koordinat (Haversine).
     * Hasil kolom distance_km bisa dipakai untuk mengurutkan terdekat.
     */
    public function scopeNearby(Builder $query, float $latitude, float $longitude, float $radiusKm = 5.0): Builder
    {
        $haversine = '(6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude))))';

        return $query
            ->selectRaw("*, {$haversine} AS distance_km", [$latitude, $longitude, $latitude])
            ->whereRaw("{$haversine} <= ?", [$latitude, $longitude, $latitude, $radiusKm]);
    }

    /** Apakah buka sekarang? (sederhana, belum memperhitungkan zona waktu khusus) */
    public function isOpenNow(): bool
    {
        $today = $this->openingHours()->where('day', now()->dayOfWeekIso % 7)->first();

        if (! $today || $today->is_closed || ! $today->open_time || ! $today->close_time) {
            return false;
        }

        $now = now()->format('H:i:s');

        return $now >= $today->open_time && $now <= $today->close_time;
    }
}
