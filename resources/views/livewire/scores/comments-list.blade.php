<div class="article__tabs__appreciations__comments">
    <div class="article__tabs__appreciations__comments__header">
        <h5 class="article__tabs__appreciations__comments__title article__tabs__sub-title">@lang('titles.comments')</h5>
        <div class="article__tabs__appreciations__comments__header__filters">
            @if (count($scores) > 0)
                <form class="form article__tabs__appreciations__comments__header__filters__form">
                    <select wire:model.live='sortComments' name="sort" id="sortComments">
                        <option value=""></option>
                        <option value="date-desc" selected>Date &darr;</option>
                        <option value="date-asc">Date &uarr;</option>
                        <option value="score-desc">Score &darr;</option>
                        <option value="score-asc">Score &uarr;</option>
                    </select>
                </form>
            @endif
            @auth
                @if (!$article->scores()->whereUserId(auth()->user()->id)->exists())
                    <x-button wire:click='toggleEvaluation'>@lang('buttons.evaluate')</x-button>
                @endif
            @endauth
        </div>
    </div>
    <div class="article__tabs__appreciations__comments__list">
        @if ($isEvaluating)
            <form wire:submit='sendComment'
                class="form card-na article__tabs__appreciations__comments__list__evaluation evaluation">
                <div class="evaluation__score">
                    <h6>@lang('titles.score')</h6>
                    <div class="evaluation__score__actions" x-data="{ score: 2.5 }">
                        <input type="range" min="1" max="5" step="0.1" id="scoreSlider"
                            x-model="score" wire:model.live.debounce.2s='score'>
                        <div class="evaluation__score__actions__input-value">
                            <input type="number" min="1" max="5" step="0.1" id="scoreInput"
                                x-model="score" wire:model.live.debounce.2s='score'>
                            <p>/5</p>
                        </div>
                    </div>
                </div>
                <div class="evaluation__comment">
                    <h6>@lang('titles.comment')</h6>
                    <x-inputs.form-base-input wireModel="comment" for="comment" type="textarea" label="comment"
                        localization="comment" />
                </div>
                <x-button type="submit">@lang('buttons.evaluate')</x-button>
            </form>
        @endif
        @foreach ($scores as $index => $score)
            <x-scores.comment :key="$index" :$article :$shop :$score />
        @endforeach
        @if (count($scores) === 0)
            <p class="empty-message">@lang('titles.no_scores')</p>
        @endif
        <p class="hidden" itemprop="reviewCount">{!! $this->scores->count() !!}</p>
    </div>
    @if ($article->scores()->count() > 3 && $offset + 3 < $article->scores()->count())
        <div class="article__tabs__appreciations__comments__load-more">
            <x-button wire:click='nextScores' style="outlined">@lang('buttons.load_more_comments')</x-button>
        </div>
    @endif
</div>
