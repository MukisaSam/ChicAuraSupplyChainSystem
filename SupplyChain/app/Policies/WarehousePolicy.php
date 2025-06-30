<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Warehouse;

class WarehousePolicy
{
    /**
     * Determine if the given warehouse can be updated by the user.
     */
    public function update(User $user, Warehouse $warehouse)
    {
        return $user->role === 'manufacturer' && $user->manufacturer && $warehouse->manufacturer_id === $user->manufacturer->id;
    }

    /**
     * Determine if the given warehouse can be deleted by the user.
     */
    public function delete(User $user, Warehouse $warehouse)
    {
        return $user->role === 'manufacturer' && $user->manufacturer && $warehouse->manufacturer_id === $user->manufacturer->id;
    }
} 