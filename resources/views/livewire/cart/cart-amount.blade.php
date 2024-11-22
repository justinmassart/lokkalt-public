<div class="article__top__infos__actions">
    <div class="article__top__infos__actions__btns">
        @if ($this->isArticleInCart)
            <x-button wire:click="removeArticleFromCart('{!! $shopArticle->hashed_id !!}')" style="filled"
                class="card__btns__btn article-card__btns__btn">@lang('buttons.remove_from_cart')</x-button>
        @else
            <x-button wire:click="addArticleToCart('{!! $shopArticle->hashed_id !!}')" style="outlined"
                class="card__btns__btn article-card__btns__btn">@lang('buttons.add_to_cart')</x-button>
        @endif
        {{-- @auth
            @if (auth()->user()->cart &&
    auth()->user()->cart->articles->contains($shopArticle->hashed_id))
                <x-button wire:click="removeArticleFromCart('{!! $shopArticle->hashed_id !!}')" style="filled"
                    class="card__btns__btn article-card__btns__btn">@lang('buttons.remove_from_cart')</x-button>
            @else
                <x-button wire:click="addArticleToCart('{!! $shopArticle->hashed_id !!}')" style="outlined"
                    class="card__btns__btn article-card__btns__btn">@lang('buttons.add_to_cart')</x-button>
            @endif
        @endauth
        @guest
            @if (array_key_exists($shopArticle->hashed_id, $this->articlesInCart))
                <x-button wire:click="removeArticleFromCart('{!! $shopArticle->hashed_id !!}')" style="filled"
                    class="card__btns__btn article-card__btns__btn">@lang('buttons.remove_from_cart')</x-button>
            @else
                <x-button wire:click="addArticleToCart('{!! $shopArticle->hashed_id !!}')" style="outlined"
                    class="card__btns__btn article-card__btns__btn">@lang('buttons.add_to_cart')</x-button>
            @endif
        @endguest --}}
        <x-button wire:click.prevent="buyNow('{!! $shopArticle->hashed_id !!}')">Acheter
            maintenant</x-button>
    </div>
</div>
