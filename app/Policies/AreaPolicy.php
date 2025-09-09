<?php

namespace App\Policies;

use App\Models\Area;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AreaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['CompanyAdmin', 'AreaManager']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Area $area): bool
    {
        // Company admins can view any area in their company
        if ($user->hasRole('CompanyAdmin')) {
            return $user->company_id === $area->company_id;
        }
        
        // Area managers can only view their assigned area
        if ($user->hasRole('AreaManager')) {
            return $user->area_id === $area->id && $user->company_id === $area->company_id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only company admins can create areas
        return $user->hasRole('CompanyAdmin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Area $area): bool
    {
        // Only company admins can update areas
        if ($user->hasRole('CompanyAdmin')) {
            return $user->company_id === $area->company_id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Area $area): bool
    {
        // Prevent deletion if there are associated staff or complaints
        if ($area->staff()->exists() || $area->complaints()->exists()) {
            return false;
        }
        
        // Only company admins can delete areas
        if ($user->hasRole('CompanyAdmin')) {
            return $user->company_id === $area->company_id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Area $area): bool
    {
        // Only company admins can restore areas
        if ($user->hasRole('CompanyAdmin')) {
            return $user->company_id === $area->company_id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Area $area): bool
    {
        // Only company admins can force delete areas
        if ($user->hasRole('CompanyAdmin')) {
            // Only allow force delete if there are no associated staff or complaints
            if ($area->staff()->withTrashed()->exists() || $area->complaints()->withTrashed()->exists()) {
                return false;
            }
            
            return $user->company_id === $area->company_id;
        }
        
        return false;
    }
    
    /**
     * Determine whether the user can toggle the status of the area.
     */
    public function toggleStatus(User $user, Area $area): bool
    {
        // Only company admins can toggle area status
        if ($user->hasRole('CompanyAdmin')) {
            return $user->company_id === $area->company_id;
        }
        
        return false;
    }
}
