<?php

namespace App\Policies;

use App\Models\SuppliedItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SuppliedItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'supplier';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SuppliedItem $suppliedItem): bool
    {
        return $user->role === 'supplier' && $suppliedItem->supplier_id === $user->supplier->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'supplier';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SuppliedItem $suppliedItem): bool
    {
        return $user->role === 'supplier' && $suppliedItem->supplier_id === $user->supplier->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SuppliedItem $suppliedItem): bool
    {
        return false; // Suppliers cannot delete supplied items
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SuppliedItem $suppliedItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SuppliedItem $suppliedItem): bool
    {
        return false;
    }
} 