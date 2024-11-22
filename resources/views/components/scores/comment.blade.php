<div wire:key="{!! $key !!}" class="card-na article-comment-card" itemscope
    itemtype="https://schema.org/Review">
    <div class="article-comment-card__image">
        {{-- TODO: user avatar --}}
        <img width="110" height="110" src="{!! asset('storage/img/avatars/avatar-test.jpg') !!}" alt="Image of Firstname L.">
    </div>
    <div class="article-comment-card__infos">
        <div class="article-comment-card__infos__name-score-options">
            <h6 class="article-comment-card__infos__name-score-options__name" itemprop="author" itemscope
                itemtype="https://schema.org/Person"><span itemprop="name">{{ $score->user->firstname }}
                    {{ substr($score->user->lastname, 0, 1) }}.</span></h6>
            <x-scores.stars :score="$score->score" />
            <p class="hidden" itemprop="ratingValue">{!! $score->score !!}</p>
            {{--             <div class="article-comment-card__infos__name-score-options__options">
                <x-icons name="options" />
            </div> --}}
        </div>
        <div class="hidden" itemprop="itemReviewed" itemscope itemtype="https://schema.org/Product">
            <p class="hidden" itemprop="name">{!! $article->name !!}</p>
            <p class="hidden" itemprop="manufacturer">{!! $shop->name !!}</p>
            <p class="hidden" itemprop="address">{!! $shop->address !!}</p>
            <div class="hidden" itemprop="aggregateRating" itemtype="https://schema.org/AggregateRating" itemscope>
                <meta itemprop="reviewCount" content="{!! $shop->scores()->count() !!}" />
                <meta itemprop="ratingValue" content="{!! $shop->scores()->avg('score') !!}" />
            </div>
        </div>
        <p class="article-comment-card__infos__comment" itemprop="reviewBody">{!! $score->comment !!}</p>
        <div class="article-comment-card__infos__actions">
            <p class="article-comment-card__infos__actions__date">{!! $score->created_at->diffForHumans() !!}</p>
        </div>
        @if ($score->answer && $score->comment)
            <p class="article-comment-card__infos__answer">{!! $score->answer->answer !!}</p>
            <p class="article-comment-card__infos__answerDate">{!! $shop->name !!} @lang('titles.answered_at')
                {{ $score->answer->created_at->format('d/m/Y') }}</p>
        @endif
    </div>
</div>
