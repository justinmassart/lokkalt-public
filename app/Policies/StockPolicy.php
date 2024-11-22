<?php

namespace App\Policies;

use App\Models\Stock;
use App\Models\User;

class StockPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return auth()->check() && session()->has('shop') && session()->has('franchise');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Stock $stock): bool
    {
        $shop = session()->get('shop');

        return auth()->check() && session()->has('shop') && session()->has('franchise') && $stock->shopArticle->shop_id === $shop->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Stock $stock): bool
    {
        $franchise = session()->get('franchise');
        $shop = session()->get('shop');

        return auth()->check() && $franchise && $franchise->subscription && session()->has('shop') && $stock->shopArticle->shop_id === $shop->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Stock $stock): bool
    {
        return $user->isSeller();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Stock $stock): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Stock $stock): bool
    {
        return $user->isSeller();
    }

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
