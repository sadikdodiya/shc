<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'name',
        'city',
        'state',
        'country',
        'pincode',
        'address',
        'contact_person',
        'contact_number',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the company that owns the area.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the staff members associated with the area.
     */
    public function staff(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the complaints associated with the area.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Scope a query to only include active areas.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include areas for a specific company.
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Get the full address of the area.
     */
    public function getFullAddressAttribute(): string
    {
        $address = [];
        if ($this->address) {
            $address[] = $this->address;
        }
        $address[] = $this->city;
        $address[] = $this->state;
        $address[] = $this->pincode;
        $address[] = $this->country;
        
        return implode(', ', array_filter($address));
    }

    /**
     * Toggle the status of the area.
     */
    public function toggleStatus()
    {
        $this->status = $this->status === 'active' ? 'inactive' : 'active';
        return $this->save();
    }
}
