<div>
    <div class="orders__list">
        @php
            $feePercentage = config('services.stripe.fee_percentage');
            $feeFixed = config('services.stripe.fee_fixed');
        @endphp
        @foreach ($this->orders as $order)
            <div wire:key="{!! $order->id !!}" class="orders__list__item card-na" x-data="{ open: false }">
                <div class="orders__list__item__header" @click="open = !open">
                    <p class="orders__list__item__header__date">{!! $order->created_at->format('d-m-Y') !!}</p>
                    <p class="orders__list__item__header__ref">{!! $order->reference !!}</p>
                    <p class="orders__list__item__header__price">{!! $order->total !!} €</p>
                    <p class="orders__list__item__header__status">{!! $order->status !!}</p>
                    <div class="orders__list__item__header__action">
                        <p class="link">@lang('titles.details')</p>
                        <span class="header__bottom__nav__item__chervron chevron down"
                            :class="{ 'up': open, 'down': !open }"><x-icons name="chevron" /></span>
                    </div>
                </div>
                <div class="orders__list__item__content" x-cloak x-show="open" x-transition:enter.duration.125ms
                    x-transition:leave.duration.125ms>
                    @foreach ($order->items as $item)
                        <div wire:key="{!! $order->reference . '.' . $item->shopArticle->article->reference !!}" class="orders__list__item__content__article">
                            @php
                                $mainImage = $item->shopArticle->article
                                    ->images()
                                    ->where('is_main_image', true)
                                    ->first();

                                $image = $mainImage
                                    ? $mainImage->url
                                    : $item->shopArticle->article->images()->first()->url;

                                $url = Illuminate\Support\Facades\Cache::get('small_file_url_' . $image);

                                if (!$url) {
                                    $url = Storage::disk('s3')->temporaryUrl(
                                        'web/small/' . $image,
                                        now()->addMinutes(2),
                                    );
                                    Illuminate\Support\Facades\Cache::put(
                                        'small_file_url_' . $image,
                                        $url,
                                        now()->addMinutes(2),
                                    );
                                }
                            @endphp
                            <div class="orders__list__item__content__article__image">
                                <img src="{!! $url !!}" alt="">
                            </div>
                            <div class="orders__list__item__content__article__infos">
                                <p>@lang('titles.article') : {!! $item->shopArticle->article->name !!}</p>
                                <p>@lang('titles.variant') : {!! $item->shopArticle->variant->name !!}</p>
                            </div>
                            <div class="orders__list__item__content__article__shop">
                                <p>@lang('titles.shop') : {!! $item->shopArticle->shop->name !!}</p>
                                <p>@lang('titles.address') : {!! $item->shopArticle->shop->postal_code . ' ' . $item->shopArticle->shop->city !!}</p>
                            </div>
                            <div class="orders__list__item__content__article__details">
                                <p>@lang('titles.quantity') : {!! $item->quantity !!}</p>
                                <p>@lang('titles.sub_total') : {!! $item->price !!} €</p>
                                <p>@lang('titles.total') : {!! $item->price * $item->quantity !!} €</p>
                                <p>@lang('titles.status') : {!! $item->has_been_refunded ? __('titles.refunded') : $item->status !!}</p>
                            </div>
                        </div>
                    @endforeach
                    <div class="orders__list__item__content__resume">
                        <div class="orders__list__item__content__resume__delivery">
                            <p class="orders__list__item__content__resume__delivery__title">@lang('titles.delivery_method') :</p>
                            <p>@lang('titles.store_pickup')</p>
                        </div>
                        <div class="orders__list__item__content__resume__total">
                            <p class="orders__list__item__content__resume__total__title">@lang('titles.sub_total') =
                                {!! $order->sub_total !!}€</p>
                            <p>@lang('titles.fees') : {!! $feePercentage !!}% + {!! $feeFixed !!}€</p>
                            <p>@lang('titles.total') = {!! $order->total !!}€</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $this->orders->links(data: ['scrollTo' => '.orders__list']) }}
</div>
