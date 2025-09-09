<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'price',
        'duration_months',
        'staff_limit',
        'start_date',
        'end_date',
        'status',
        'features',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'string',
        'features' => 'array',
    ];

    /**
     * The possible package statuses.
     */
    public const STATUS_ACTIVE = 'active';
    public const STATUS_PENDING = 'pending';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the company that owns the package.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Check if the package is active.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE && 
               $this->end_date->isFuture();
    }

    /**
     * Check if the package is expired.
     */
    public function isExpired(): bool
    {
        return $this->end_date->isPast();
    }

    /**
     * Get the remaining days of the package.
     */
    public function remainingDays(): int
    {
        return now()->diffInDays($this->end_date, false);
    }
}
