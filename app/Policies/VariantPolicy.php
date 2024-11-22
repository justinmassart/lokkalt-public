<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Variant;

class VariantPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Variant $variant): bool
    {
        return $user->isSeller();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isSeller();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Variant $variant): bool
    {
        return $user->isSeller();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Variant $variant): bool
    {
        return $user->isSeller();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Variant $variant): bool
    {
        return $user->isSeller();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Variant $variant): bool
    {
        return $user->isSeller();
    }

    /**
     * The following policies are the one expected by Filament that Laravel doesn't include.
     */
    public function deleteAny(User $user): bool
    {
        return $user->isSeller();
    }

    public function restoreAny(User $user): bool
    {
        return $user->isSeller();
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->isSeller();
    }
}
