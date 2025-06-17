<?php

namespace App\Policies;

use App\Models\SupplyRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplyRequestPolicy
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
    public function view(User $user, SupplyRequest $supplyRequest): bool
    {
        return $user->role === 'supplier' && $supplyRequest->supplier_id === $user->supplier->id;
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
    public function update(User $user, SupplyRequest $supplyRequest): bool
    {
        return $user->role === 'supplier' && $supplyRequest->supplier_id === $user->supplier->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SupplyRequest $supplyRequest): bool
    {
        return false; // Suppliers cannot delete supply requests
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SupplyRequest $supplyRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SupplyRequest $supplyRequest): bool
    {
        return false;
    }
} 