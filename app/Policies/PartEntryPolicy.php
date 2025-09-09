<?php

namespace App\Policies;

use App\Models\PartEntry;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartEntryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasRole(['CompanyAdmin', 'InventoryManager']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PartEntry  $partEntry
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, PartEntry $partEntry)
    {
        return $user->company_id === $partEntry->item->company_id && 
               $user->hasRole(['CompanyAdmin', 'InventoryManager']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasRole(['CompanyAdmin', 'InventoryManager']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PartEntry  $partEntry
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, PartEntry $partEntry)
    {
        // Only allow updates if the entry is from the user's company
        // and the entry is not older than 1 day
        return $user->company_id === $partEntry->item->company_id && 
               $partEntry->created_at->diffInDays(now()) <= 1 &&
               $user->hasRole(['CompanyAdmin', 'InventoryManager']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PartEntry  $partEntry
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PartEntry $partEntry)
    {
        // Only allow deletion if the entry is from the user's company
        // and the entry is not older than 1 day
        return $user->company_id === $partEntry->item->company_id && 
               $partEntry->created_at->diffInDays(now()) <= 1 &&
               $user->hasRole(['CompanyAdmin']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PartEntry  $partEntry
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PartEntry $partEntry)
    {
        return $user->company_id === $partEntry->item->company_id && 
               $user->hasRole(['CompanyAdmin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PartEntry  $partEntry
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PartEntry $partEntry)
    {
        return $user->hasRole(['CompanyAdmin']);
    }

    /**
     * Determine whether the user can manage stock.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function manageStock(User $user)
    {
        return $user->hasRole(['CompanyAdmin', 'InventoryManager']);
    }
}
