@props(['category_name', 'category_image'])
<div class="card category-card category-card-2C">
    <a class="card-link" title="{!! __('links.link') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
        href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.category', ['category' => str()->slug($category_name)]) }}">{!! __('links.category', ['category_name' => $category_name]) !!}</a>
    <div class="card__image category-card__image category-card-2C__image">
        <div class="card__image__gradient category-card__image__gradient"></div>
        @php
            $url = Cache::get('file_url_' . str()->slug($category_name));

            if (!$url) {
                $url = asset('storage/img/categories/' . str()->slug($category_name) . '.webp');
                Cache::put('file_url_' . str()->slug($category_name), $url, now()->addHours(10));
            }
        @endphp
        <img width="230" height="350" src="{{ $url }}" alt="{!! __('image.description', ['subject' => $category_name]) !!}">
    </div>
    <div class="card__name category-card__name
            category-card-2C__name">
        <h3 class="card__name__title category-card__name__title category-card-2C__name">@lang('categories.' . str()->slug($category_name))</h3>
    </div>
</div>
