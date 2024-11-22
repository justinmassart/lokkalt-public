{{-- <div class="modal-container" x-cloak x-show="modal" x-transition:enter.duration.400ms
    x-transition:leave.duration.200ms @keydown.escape.window="modal = false">
    <div class="modal" x-init="$watch('modal', value => document.body.classList.toggle('no-scroll', value))" @click.away="modal = false">
        <div class="modal__header">
            <div class="modal__header__left">
                <h3 class="modal__header__left__title">@lang('titles.variants_out_of_stock')</h3>
            </div>
            <div class="modal__header__right" @click.prevent="modal= false">
                <x-icons name="cross" />
            </div>
        </div>
        @if (count($this->variantsOutOfStock) > 0)
            <div class="modal__content">
                <div class="modal__content__container">
                    <h2>Some articles in your cart cannot be ordered with the requested quantity. Please change the
                        requested quantity or remove the article from your cart to be able to order.</h2>
                    <div class="modal__content__container__list">
                        @foreach ($this->variantsOutOfStock as $variant)
                            <div class="modal__content__container__list__item">
                                <p>{!! $variant->article->name !!} - {!! $variant->name !!}</p>
                                <p>Asked quantity : {!! $cartArticles[$variant->id]['quantity'] !!}</p>
                                @if ($variant->stock->quantity > 0)
                                    <p>Available quantity : {!! $variant->stock->quantity !!}</p>
                                @else
                                    <p>Available quantity : Out of stock</p>
                                @endif
                                <div class="modal__content__container__list__item__actions">
                                    <form method="POST" class="cart-card__price__actions__count form">
                                        <label class="hidden" for="cart-item-count">@lang('inputs.quantity')</label>
                                        <span for="cart-item-count">@lang('inputs.quantity_txt'):</span>
                                        <select wire:model.change='cartArticles.{!! $variant->id !!}.quantity'
                                            name="cart-item-count" id="cart-item-count">
                                            <option value="0">0</option>
                                            @for ($i = 0; $i <= $variant->stock->quantity; $i++)
                                                <option {!! $quantity === $i ? 'selected' : null !!} value="{!! $i !!}">
                                                    {!! $i !!}</option>
                                            @endfor
                                        </select>
                                    </form>
                                    <x-button :key="$variant->id" color="red" style="outlined" icon="trash-lid"
                                        wire:click="removeArticleFromCart('{!! $variant->id !!}')"></x-button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div> --}}
