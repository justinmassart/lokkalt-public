<div>
    <div class="card-na article-total-score-card">
        <div class="article-total-score-card__score-count">
            <div class="article-total-score-card__score-count__container">
                <x-scores.stars :score="number_format($scoreAvg, 2)" />
                <p class="article-total-score-card__score-count__total">{!! number_format($scoreAvg, 2) !!}</p>
            </div>
            <p class="article-total-score-card__score-count__count">{!! $scoreCount !!} @lang('titles.votes')</p>
        </div>
        <div class="article-total-score-card__details">
            <ol class="article-total-score-card__details__list">
                @foreach ($scoreBoard as $score => $count)
                    <li class="article-total-score-card__details__list__item">
                        <span
                            class="article-total-score-card__details__list__item__number">{!! $score !!}</span>
                        <div class="article-total-score-card__details__list__item__rectangles">
                            <span class="article-total-score-card__details__list__item__rectangles__grey"></span>
                            <span class="article-total-score-card__details__list__item__rectangles__green"
                                style="right: calc(100% - {!! $scoreCount > 0 ? ($scoreBoard[$score] / $scoreCount) * 100 : 0 !!}%)"></span>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</div>
