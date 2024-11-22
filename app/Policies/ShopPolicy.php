<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;

class ShopPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->check() && session()->has('franchise') && session()->get('franchise')->subscription;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Shop $shop): bool
    {
        return auth()->check() && $user->canAccessShop($shop);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $franchise = session()->get('franchise');

        return auth()->check() && $franchise && $franchise->subscription;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Shop $shop): bool
    {
        $franchise = session()->get('franchise');

        return auth()->check() && $franchise && $franchise->subscription && auth()->user()->canAccessShop($shop);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Shop $shop): bool
    {
        return $user->isSeller();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Shop $shop): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Shop $shop): bool
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
        return false;
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->isSeller();
    }
}
