<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Hash;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'alt_phone',
        'password',
        'status',
        'company_id',
        'email_verified_at',
        'address',
        'city',
        'state',
        'pincode',
        'emergency_contact',
        'emergency_phone',
        'joining_date',
        'dob',
        'aadhar_number',
        'pan_number',
        'bank_name',
        'account_number',
        'ifsc_code',
        'salary_type',
        'salary',
        'allow_part_deduction',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'joining_date' => 'date',
        'dob' => 'date',
        'status' => 'boolean',
        'allow_part_deduction' => 'boolean',
        'salary' => 'decimal:2',
    ];

    /**
     * Get the company that owns the user.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the complaints assigned to the user.
     */
    public function assignedComplaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'assigned_staff_id');
    }

    /**
     * Get the payments for the user.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(StaffPayment::class, 'staff_id');
    }

    /**
     * Get the user's current balance.
     */
    public function getBalanceAttribute(): float
    {
        return $this->payments()->sum(
            fn($payment) => $payment->type === StaffPayment::TYPE_CREDIT 
                ? $payment->amount 
                : -$payment->amount
        );
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        if (is_array($role)) {
            return $this->roles->whereIn('name', $role)->isNotEmpty();
        }

        if ($role instanceof \Illuminate\Support\Collection) {
            return $role->intersect($this->roles)->isNotEmpty();
        }

        return false;
    }

    /**
     * Check if the user is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Find the user instance for the given username.
     *
     * @param  string  $username
     * @return \App\Models\User
     */
    public function findForPassport($username)
    {
        return $this->where('email', $username)
            ->orWhere('phone', $username)
            ->first();
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    /**
     * Get the user's documents.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get the count of user's documents.
     */
    public function getDocumentCountAttribute(): int
    {
        return $this->documents()->count();
    }

    /**
     * Check if the user has reached the document limit.
     */
    public function hasReachedDocumentLimit(): bool
    {
        $maxDocuments = $this->company->package->max_documents_per_staff ?? 10;
        return $this->documents()->count() >= $maxDocuments;
    }

    /**
     * Get the storage path for user's documents.
     */
    public function getDocumentStoragePath(string $subDirectory = ''): string
    {
        $path = "companies/{$this->company_id}/staff/{$this->id}/documents";
        
        if (!empty($subDirectory)) {
            $path .= '/' . trim($subDirectory, '/');
        }
        
        return $path;
    }

    /**
     * Upload a document for the user.
     */
    public function uploadDocument($file, string $description = null): Document
    {
        $path = $file->store($this->getDocumentStoragePath(), 'public');
        
        return $this->documents()->create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'description' => $description,
        ]);
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
