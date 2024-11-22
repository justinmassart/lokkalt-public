@props(['category_name', 'sub_category_name', 'sub_category_image', 'key'])
<div wire:key="{!! $key !!}" class="card category-card category-card-2C-small">
    <a class="card-link" title="{!! __('links.link') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
        href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.category', [
            'category' => str()->slug($category_name),
            'subCategory' => str()->slug($sub_category_name),
        ]) !!}">{!! __('links.category', ['category_name' => $category_name]) !!}">{!! __('links.category', ['sub_category_name' => $sub_category_name]) !!}</a>
    <div class="card__image category-card__image category-card-2C-small__image">
        <div class="card__image__gradient category-card__image__gradient"></div>
        @php
            $url = Cache::get('file_url_' . str()->slug($category_name));

            if (!$url) {
                $url = asset('storage/img/categories/' . str()->slug($category_name) . '.webp');
                Cache::put('file_url_' . str()->slug($category_name), $url, now()->addHours(10));
            }
        @endphp
        <img width="230" height="230" src="{{ $url }}" alt="{!! __('image.description', ['subject' => $sub_category_name]) !!}">
    </div>
    <div class="card__name category-card__name
            category-card-2C-small__name">
        <h3 class="card__name__title category-card__name__title category-card-2C-small__name">@lang('categories.' . str()->slug($sub_category_name))</h3>
    </div>
</div>
