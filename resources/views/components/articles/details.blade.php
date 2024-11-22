<div class="card-na article-details-card">
    <ul class="article-details-card__list">
        @foreach ($details as $detail => $value)
            <li class="article-details-card__list__item">
                <span class="article-details-card__list__item__name">{!! $detail !!}</span>
                <span class="article-details-card__list__item__value">{!! $value !!}</span>
            </li>
        @endforeach
    </ul>
</div>
