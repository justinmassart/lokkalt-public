<div class="card__image__price-favourite__favourite article-card__image__price-favourite__favourite">
    @auth
        @if (auth()->user()->favouriteArticles()->where('reference', $this->articleRef)->exists())
            <x-button wire:click="removeArticleFromFavourite" style="none"
                class="card__image__price-favourite__favourite__btn article-card__image__price-favourite__favourite__btn"
                title="{!! __('buttons.remove_from_favourites') !!}">
                <x-icons name="heart-filled" />
            </x-button>
        @else
            <x-button wire:click="addArticleToFavourite" style="none"
                class="card__image__price-favourite__favourite__btn article-card__image__price-favourite__favourite__btn"
                title="{!! __('buttons.add_to_favourites') !!}">
                <x-icons name="heart" />
            </x-button>
        @endif
    @endauth
    @guest
        <x-button wire:click="redirectUser" style="none"
            class="card__image__price-favourite__favourite__btn article-card__image__price-favourite__favourite__btn"
            title="{!! __('buttons.add_to_favourites') !!}">
            <x-icons name="heart" />
        </x-button>
    @endguest
</div>
