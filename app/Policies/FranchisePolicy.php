<?php

namespace App\Policies;

use App\Models\Franchise;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FranchisePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->check() && $user->isSeller();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Franchise $franchise): bool
    {
        return auth()->check() && $franchise->hasOwner($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return auth()->check() && $user->isSeller();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Franchise $franchise): bool
    {
        return auth()->check() && $franchise->hasOwner($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Franchise $franchise): bool
    {
        return auth()->check() && $franchise->hasOwner($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Franchise $franchise): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Franchise $franchise): bool
    {
        return auth()->check() && $franchise->hasOwner($user);
    }

    public function deleteAny(User $user): bool
    {
        return auth()->check() && $user->isSeller();
    }

    public function restoreAny(User $user): bool
    {
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return auth()->check() && $user->isSeller();
    }
}
