<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function updateProfile(User $authenticatedUser, User $userToUpdate)
    {
        return $authenticatedUser->id === $userToUpdate->id;
    }

    public function updateAny(User $user)
    {
        return $user->role === 'admin'; 
    }
}
