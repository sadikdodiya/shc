<?php

namespace App\Policies;

use App\Models\FaultType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FaultTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('CompanyAdmin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FaultType $faultType): bool
    {
        return $user->company_id === $faultType->company_id && 
               $user->hasRole('CompanyAdmin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('CompanyAdmin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FaultType $faultType): bool
    {
        return $user->company_id === $faultType->company_id && 
               $user->hasRole('CompanyAdmin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FaultType $faultType): bool
    {
        // Prevent deletion if there are associated complaints
        if ($faultType->complaints()->exists()) {
            return false;
        }
        
        return $user->company_id === $faultType->company_id && 
               $user->hasRole('CompanyAdmin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FaultType $faultType): bool
    {
        return $user->company_id === $faultType->company_id && 
               $user->hasRole('CompanyAdmin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FaultType $faultType): bool
    {
        // Only allow force delete if there are no associated complaints
        if ($faultType->complaints()->withTrashed()->exists()) {
            return false;
        }
        
        return $user->company_id === $faultType->company_id && 
               $user->hasRole('CompanyAdmin');
    }
    
    /**
     * Determine whether the user can toggle the status of the model.
     */
    public function toggleStatus(User $user, FaultType $faultType): bool
    {
        return $user->company_id === $faultType->company_id && 
               $user->hasRole('CompanyAdmin');
    }
}
