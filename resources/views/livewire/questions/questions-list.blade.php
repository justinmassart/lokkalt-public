<div>
    <div class="article__tabs__questions__header">
        <h4 class="article__tabs__questions__title article__tabs__sub-title">@lang('titles.recent_questions')</h4>
        @auth
            <x-button wire:click="toggleAddQuestion">@lang('buttons.add_question')</x-button>
        @endauth
    </div>
    <div class="article__tabs__questions__list">
        @if ($this->questions->isNotEmpty())
            @php
                $questions = $this->questions->items();
                $half = ceil(count($questions) / 2);
                $leftQuestions = array_slice($questions, 0, $half);
                $rightQuestions = array_slice($questions, $half);
            @endphp
            <div class="article__tabs__questions__list__left">
                @foreach ($leftQuestions as $lq)
                    <x-interactions.question :$shop :question="$lq" />
                @endforeach
            </div>
            <div class="article__tabs__questions__list__right">
                @foreach ($rightQuestions as $rq)
                    <x-interactions.question :$shop :question="$rq" />
                @endforeach
            </div>
            <script>
                function toggleAnswer(element) {
                    element.classList.toggle("expanded");
                }

                function togglePackSelection(element) {
                    element.classList.toggle("pack-selected");
                }
            </script>
        @else
            <p class="empty-message">@lang('titles.no_questions')</p>
        @endif
    </div>
    @if ($this->questions->isNotEmpty())
        {{ $this->questions->links(data: ['scrollTo' => '.article__tabs__questions']) }}
    @endif
    @if ($addQuestion)
        <form wire:submit='sendQuestion' class="form card-na" method="POST">
            <x-inputs.form-base-input wireModel="question" for="article-question" type="textarea" label="question"
                localization="question" />
            <x-button type="submit">@lang('buttons.send_question')</x-button>
        </form>
    @elseif ($question && !$addQuestion)
        <p class="question-sent">Votre question a bien été envoyée, elle sera visible une fois que
            {!! $shop->name !!} y aura répondu.</p>
    @endif
</div>
