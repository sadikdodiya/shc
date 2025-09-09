<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('CompanyAdmin') && $user->company_id !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Item $item): bool
    {
        return $user->company_id === $item->company_id && 
               ($user->hasRole('CompanyAdmin') || $user->hasRole('InventoryManager'));
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('CompanyAdmin') || $user->hasRole('InventoryManager');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Item $item): bool
    {
        return $user->company_id === $item->company_id && 
               ($user->hasRole('CompanyAdmin') || $user->hasRole('InventoryManager'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Item $item): bool
    {
        // Prevent deletion if there are part entries
        if ($item->partEntries()->exists()) {
            return false;
        }
        
        return $user->company_id === $item->company_id && 
               $user->hasRole('CompanyAdmin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Item $item): bool
    {
        return $user->company_id === $item->company_id && 
               $user->hasRole('CompanyAdmin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Item $item): bool
    {
        // Prevent force delete if there are part entries
        if ($item->partEntries()->withTrashed()->exists()) {
            return false;
        }
        
        return $user->company_id === $item->company_id && 
               $user->hasRole('CompanyAdmin');
    }
    
    /**
     * Determine whether the user can update the stock of the item.
     */
    public function updateStock(User $user, Item $item): bool
    {
        return $user->company_id === $item->company_id && 
               ($user->hasRole('CompanyAdmin') || $user->hasRole('InventoryManager'));
    }
}
