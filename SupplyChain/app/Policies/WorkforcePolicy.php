<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workforce;

class WorkforcePolicy
{
    /**
     * Determine if the given workforce can be updated by the user.
     */
    public function update(User $user, Workforce $workforce)
    {
        return $user->role === 'manufacturer' && $user->manufacturer && $workforce->manufacturer_id === $user->manufacturer->id;
    }

    /**
     * Determine if the given workforce can be deleted by the user.
     */
    public function delete(User $user, Workforce $workforce)
    {
        return $user->role === 'manufacturer' && $user->manufacturer && $workforce->manufacturer_id === $user->manufacturer->id;
    }

    /**
     * Determine if the given workforce can be viewed by the user.
     */
    public function view(User $user, Workforce $workforce)
    {
        return $user->role === 'manufacturer' && $user->manufacturer && $workforce->manufacturer_id === $user->manufacturer->id;
    }
} 