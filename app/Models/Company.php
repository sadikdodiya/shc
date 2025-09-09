<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'status',
        'website',
        'logo',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the admin user associated with the company.
     */
    public function admin(): HasOne
    {
        return $this->hasOne(User::class, 'company_id')->whereHas('roles', function($query) {
            $query->where('name', 'CompanyAdmin');
        });
    }

    /**
     * Get the users for the company.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the packages for the company.
     */
    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    /**
     * Get the active package for the company.
     */
    public function activePackage()
    {
        return $this->packages()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->latest()
            ->first();
    }
}
