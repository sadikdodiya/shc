<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffPayment extends Model
{
    use HasFactory;

    public const TYPE_CREDIT = 'credit';
    public const TYPE_DEBIT = 'debit';

    protected $fillable = [
        'staff_id',
        'company_id',
        'amount',
        'type',
        'payment_date',
        'payment_method',
        'reference',
        'description',
        'is_salary_advance',
        'status',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'is_salary_advance' => 'boolean',
    ];

    /**
     * Get the staff member associated with the payment.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Get the company that owns the payment.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the admin who approved the payment.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope a query to only include credit transactions.
     */
    public function scopeCredits($query)
    {
        return $query->where('type', self::TYPE_CREDIT);
    }

    /**
     * Scope a query to only include debit transactions.
     */
    public function scopeDebits($query)
    {
        return $query->where('type', self::TYPE_DEBIT);
    }

    /**
     * Get the current balance for a staff member.
     */
    public static function getBalance($staffId): float
    {
        $credits = static::where('staff_id', $staffId)
            ->credits()
            ->sum('amount');

        $debits = static::where('staff_id', $staffId)
            ->debits()
            ->sum('amount');

        return $credits - $debits;
    }
}
