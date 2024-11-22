<div class="article__top__images single-page__top__images">
    @foreach ($images as $index => $i)
        <div class="article__top__images__image single-page__top__images__image">
            @php
                $url = Cache::get('file_url_' . $i);

                if (!$url) {
                    $url = Storage::disk('s3')->temporaryUrl('web/big/' . $i, now()->addMinutes(30));
                    Cache::put('file_url_' . $i, $url, now()->addMinutes(30));
                }
            @endphp
            @if ($index === 0)
                <img width="730" height="448" src="{!! $url !!}"
                    alt="Image du produit {!! $article->name !!} du vendeur {!! $shop->name !!}" itemprop="image">
            @else
                @php
                    $url = Cache::get('file_url_' . $i);

                    if (!$url) {
                        $url = Storage::disk('s3')->temporaryUrl('web/medium/' . $i, now()->addMinutes(30));
                        Cache::put('file_url_' . $i, $url, now()->addMinutes(30));
                    }
                @endphp
                <img width="230" height="230" src="{!! $url !!}"
                    alt="Image du produit {!! $article->name !!} du vendeur {!! $shop->name !!}" itemprop="image">
            @endif
        </div>
    @endforeach
</div>
