<div>
    @if ($article !== null)
        @auth
            @if (auth()->user()->favouriteArticles()->where('article_id', $article->id)->exists())
                <x-button wire:click="removeArticleFromFavourite('{!! $article->id !!}')"
                    style="filled">@lang('buttons.remove_from_favourites')</x-button>
            @else
                <x-button wire:click="addArticleToFavourite('{!! $article->id !!}')"
                    style="outlined">@lang('buttons.add_to_favourites')</x-button>
            @endif
        @endauth
        @guest
            <x-button wire:click='redirectUser' style="outlined">@lang('buttons.add_to_favourites')</x-button>
        @endguest
    @endif
    @if ($shop !== null)
        @auth
            @if (auth()->user()->favouriteShops()->where('shop_id', $shop->id)->exists())
                <x-button wire:click="removeShopFromFavourite('{!! $shop->id !!}')"
                    style="filled">@lang('buttons.remove_from_favourites')</x-button>
            @else
                <x-button wire:click="addShopToFavourite('{!! $shop->id !!}')"
                    style="outlined">@lang('buttons.add_to_favourites')</x-button>
            @endif
        @endauth
        @guest
            <x-button wire:click='redirectUser' style="outlined">@lang('buttons.add_to_favourites')</x-button>
        @endguest
    @endif
</div>
