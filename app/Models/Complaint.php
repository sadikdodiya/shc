<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_no',
        'company_id',
        'name',
        'email',
        'mobile',
        'alt_mobile',
        'brand_id',
        'product_id',
        'area_id',
        'fault_type_id',
        'call_type',
        'assigned_staff_id',
        'status',
        'description',
        'address',
        'landmark',
        'pincode',
        'city',
        'state',
        'purchase_date',
        'warranty_status',
        'invoice_no',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'status' => 'string',
    ];

    /**
     * Get the company that owns the complaint.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the brand that owns the complaint.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the product that owns the complaint.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the area that owns the complaint.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the fault type that owns the complaint.
     */
    public function faultType(): BelongsTo
    {
        return $this->belongsTo(FaultType::class);
    }

    /**
     * Get the staff assigned to the complaint.
     */
    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($complaint) {
            $complaint->complaint_no = static::generateComplaintNumber();
        });
    }

    /**
     * Generate a unique complaint number.
     */
    protected static function generateComplaintNumber(): string
    {
        $prefix = 'CMP';
        $date = now()->format('Ymd');
        $lastComplaint = static::where('complaint_no', 'like', "{$prefix}{$date}%")
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastComplaint 
            ? (int)substr($lastComplaint->complaint_no, -4) + 1 
            : 1;

        return sprintf("%s%s%04d", $prefix, $date, $number);
    }
}
