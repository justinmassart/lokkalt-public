<div class="card variant-card-1C">
    <a class="card-link" title="{!! __('links.variant') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
        href="{!! $link !!}">{!! __('links.variant', ['variant' => $variant->name]) !!}</a>
    @php
        $variantImage = $variant->images()->where('is_main_image', true)->first();

        $image = $variantImage
            ? $variantImage->url
            : ($variant->images()->first()
                ? $variant->images()->first()->url
                : null);

        if (!$image) {
            $image = $variant->article->images()->where('is_main_image', true)->first()
                ? $variant->article->images()->where('is_main_image', true)->first()->url
                : $variant->article->images()->first()->url;
        }

        $url = Illuminate\Support\Facades\Cache::get('small_file_url_' . $image);

        if (!$url) {
            $url = Storage::disk('s3')->temporaryUrl('web/small/' . $image, now()->addHours(10));
            Illuminate\Support\Facades\Cache::put('small_file_url_' . $image, $url, now()->addHours(10));
        }
    @endphp
    <div class="card__image variant-card-1C__image">
        <img width="105" height="105" src="{!! $url !!}" alt="{!! __('image.description', ['subject' => $variant->name]) !!}">
    </div>
    <div class="card__name variant-card-1C__name">
        <h4 class="card__name__title variant-card-1C__name__title">{!! $variant->name !!}</h4>
    </div>
</div>
