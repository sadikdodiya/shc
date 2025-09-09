<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ComplaintRemark extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'complaint_id',
        'staff_id',
        'message',
        'photo_path',
        'status',
        'additional_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'additional_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'photo_url',
        'formatted_created_at',
    ];

    /**
     * Get the complaint that owns the remark.
     */
    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    /**
     * Get the staff member who created the remark.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Get the URL for the remark's photo.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? Storage::url($this->photo_path) : null;
    }

    /**
     * Get the formatted created at timestamp.
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    /**
     * Scope a query to only include remarks for a specific complaint.
     */
    public function scopeForComplaint($query, $complaintId)
    {
        return $query->where('complaint_id', $complaintId);
    }

    /**
     * Scope a query to only include remarks by a specific staff member.
     */
    public function scopeByStaff($query, $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(function ($remark) {
            if (auth()->check()) {
                $remark->staff_id = $remark->staff_id ?? auth()->id();
            }
        });

        static::deleting(function ($remark) {
            // Delete associated photo when remark is deleted
            if ($remark->photo_path) {
                Storage::delete($remark->photo_path);
            }
        });
    }
}
