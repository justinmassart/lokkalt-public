<div class="card__btns article-card__btns">
    @if ($isArticleInCart)
        <x-button wire:click="removeArticleFromCart" style="filled"
            class="card__btns__btn article-card__btns__btn">@lang('buttons.remove_from_cart')</x-button>
    @else
        <x-button wire:click="addArticleToCart" style="outlined"
            class="card__btns__btn article-card__btns__btn">@lang('buttons.add_to_cart')</x-button>
    @endif
</div>
