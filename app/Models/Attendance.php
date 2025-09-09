<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Attendance extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_id',
        'clock_in',
        'clock_in_photo',
        'clock_out',
        'clock_out_photo',
        'latitude',
        'longitude',
        'notes',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'clock_in_photo_url',
        'clock_out_photo_url',
        'duration',
    ];

    /**
     * Get the staff member that owns the attendance record.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Get the URL for the clock-in photo.
     */
    public function getClockInPhotoUrlAttribute(): ?string
    {
        return $this->clock_in_photo ? Storage::url($this->clock_in_photo) : null;
    }

    /**
     * Get the URL for the clock-out photo.
     */
    public function getClockOutPhotoUrlAttribute(): ?string
    {
        return $this->clock_out_photo ? Storage::url($this->clock_out_photo) : null;
    }

    /**
     * Get the duration of the attendance in hours.
     */
    public function getDurationAttribute(): ?float
    {
        if (!$this->clock_in || !$this->clock_out) {
            return null;
        }

        return round($this->clock_out->diffInMinutes($this->clock_in) / 60, 2);
    }

    /**
     * Scope a query to only include today's attendance for a staff member.
     */
    public function scopeToday($query, $staffId = null)
    {
        $query->whereDate('clock_in', today())
            ->when($staffId, fn($q) => $q->where('staff_id', $staffId));
    }

    /**
     * Scope a query to only include attendance for a specific date range.
     */
    public function scopeDateRange($query, $startDate, $endDate, $staffId = null)
    {
        $query->whereBetween('clock_in', [$startDate, $endDate])
            ->when($staffId, fn($q) => $q->where('staff_id', $staffId));
    }

    /**
     * Check if the staff member is currently clocked in.
     */
    public function isClockedIn(): bool
    {
        return $this->clock_in && !$this->clock_out;
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::deleting(function ($attendance) {
            // Delete associated photos when attendance record is deleted
            if ($attendance->clock_in_photo) {
                Storage::delete($attendance->clock_in_photo);
            }
            if ($attendance->clock_out_photo) {
                Storage::delete($attendance->clock_out_photo);
            }
        });
    }
}
