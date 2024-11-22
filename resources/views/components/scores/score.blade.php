@if ($article->scores->count() > 0)
    <div class="card-na article-total-score-card">
        <div class="article-total-score-card__score-count">
            <div class="article-total-score-card__score-count__container">
                <x-scores.stars :score="$article->scores->avg('score')" />
                <p class="article-total-score-card__score-count__total">{!! number_format($article->scores->avg('score'), 2) !!}</p>
            </div>
            <p class="article-total-score-card__score-count__count">{!! $article->scores->count() !!} @lang('titles.votes')</p>
        </div>
        <div class="article-total-score-card__details">
            <ol class="article-total-score-card__details__list">
                <li class="article-total-score-card__details__list__item">
                    <span class="article-total-score-card__details__list__item__number">5</span>
                    <div class="article-total-score-card__details__list__item__rectangles">
                        <span class="article-total-score-card__details__list__item__rectangles__grey"></span>
                        <span class="article-total-score-card__details__list__item__rectangles__green"
                            style="right: calc(100% - {!! ($article->scores->where('score', '=', 5)->count() / $article->scores->count()) * 100 !!}%)"></span>
                    </div>
                </li>
                <li class="article-total-score-card__details__list__item">
                    <span class="article-total-score-card__details__list__item__number">4</span>
                    <div class="article-total-score-card__details__list__item__rectangles">
                        <span class="article-total-score-card__details__list__item__rectangles__grey"></span>
                        <span class="article-total-score-card__details__list__item__rectangles__green"
                            style="right: calc(100% - {!! ($article->scores->where('score', '>=', 4)->where('score', '<', 5)->count() / $article->scores->count()) *
                                100 !!}%)"></span>
                    </div>
                </li>
                <li class="article-total-score-card__details__list__item">
                    <span class="article-total-score-card__details__list__item__number">3</span>
                    <div class="article-total-score-card__details__list__item__rectangles">
                        <span class="article-total-score-card__details__list__item__rectangles__grey"></span>
                        <span class="article-total-score-card__details__list__item__rectangles__green"
                            style="right: calc(100% - {!! ($article->scores->where('score', '>=', 3)->where('score', '<', 4)->count() / $article->scores->count()) *
                                100 !!}%)"></span>
                    </div>
                </li>
                <li class="article-total-score-card__details__list__item">
                    <span class="article-total-score-card__details__list__item__number">2</span>
                    <div class="article-total-score-card__details__list__item__rectangles">
                        <span class="article-total-score-card__details__list__item__rectangles__grey"></span>
                        <span class="article-total-score-card__details__list__item__rectangles__green"
                            style="right: calc(100% - {!! ($article->scores->where('score', '>=', 2)->where('score', '<', 3)->count() / $article->scores->count()) *
                                100 !!}%)"></span>
                    </div>
                </li>
                <li class="article-total-score-card__details__list__item">
                    <span class="article-total-score-card__details__list__item__number">1</span>
                    <div class="article-total-score-card__details__list__item__rectangles">
                        <span class="article-total-score-card__details__list__item__rectangles__grey"></span>
                        <span class="article-total-score-card__details__list__item__rectangles__green"
                            style="right: calc(100% - {!! ($article->scores->where('score', '>=', 1)->where('score', '<', 2)->count() / $article->scores->count()) *
                                100 !!}%)"></span>
                    </div>
                </li>
            </ol>
        </div>
    </div>
@endif
