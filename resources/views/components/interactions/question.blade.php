<div class="card question-card" x-data="{ open: false }" @click.prevent="open = !open" :class="{ 'expanded': open }">
    <div class="question-card__question">
        <p class="question-card__question__text">{!! $question->content !!}</p>
        <x-icons name="chevron" />
    </div>
    <div class="question-card__answer">
        <p class="question-card__answer__text">{!! $question->articleAnswer->answer !!}</p>
        <p class="question-card__answer__date">{!! $question->articleAnswer->shop->name !!} @lang('titles.answered_at')
            {{ $question->articleAnswer->created_at->format('d/m/Y') }}</p>
    </div>
</div>
