<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_USER = 'user';
    public const ROLE_OWNER = 'owner';
    public const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'photo',
        'latitude',
        'longitude',
        'points_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
        'points_balance' => 'integer',
    ];

    /* ------------------------------------------------------------------ */
    /* Relasi                                                             */
    /* ------------------------------------------------------------------ */

    /** Kafe yang dimiliki user (untuk role owner). */
    public function ownedCafes(): HasMany
    {
        return $this->hasMany(Cafe::class, 'owner_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function searchHistories(): HasMany
    {
        return $this->hasMany(SearchHistory::class);
    }

    public function wishlists(): BelongsToMany
    {
        return $this->belongsToMany(Cafe::class, 'wishlists')->withPivot('created_at');
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function voucherRedemptions(): HasMany
    {
        return $this->hasMany(VoucherRedemption::class);
    }

    public function reviewReplies(): HasMany
    {
        return $this->hasMany(ReviewReply::class);
    }

    /* ------------------------------------------------------------------ */
    /* Helper                                                             */
    /* ------------------------------------------------------------------ */

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }
}
