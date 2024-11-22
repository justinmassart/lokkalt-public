<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
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
    public function view(User $user, Article $article): bool
    {
        $franchise = session()->get('franchise');
        $shop = session()->get('shop');

        return auth()->check() && $franchise && $shop && $shop && $shop->doesOwnArticle($article);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $franchise = session()->get('franchise');
        $shop = session()->get('shop');

        return auth()->check() && $franchise && $franchise->subscription && $shop;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Article $article): bool
    {
        $franchise = session()->get('franchise');
        $shop = session()->get('shop');

        return auth()->check() && $franchise && $franchise->subscription && $shop && $shop->doesOwnArticle($article);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Article $article): bool
    {
        return $user->isSeller();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Article $article): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Article $article): bool
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
