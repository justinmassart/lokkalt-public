<div class="card-na packs-resume-card">
    <h3 class="packs-resume-card__title">@lang('titles.resume')</h3>
    <div class="packs-resume-card__resume">
        <div class="packs-resume-card__resume__selected-total">
            <p class="packs-resume-card__resume__selected-total__selected">@lang('titles.selected_packs')</p>
            <p class="packs-resume-card__resume__selected-total__total">@lang('titles.sub_total')</p>
        </div>
        <ul class="packs-resume-card__resume__list">
            <li class="packs-resume-card__resume__list__item">
                <span class="packs-resume-card__resume__list__item__pack">@lang('packs.visible_shop')</span>
                <span class="packs-resume-card__resume__list__item__price">15,00 €</span>
            </li>
            <li class="packs-resume-card__resume__list__item">
                <span class="packs-resume-card__resume__list__item__pack">@lang('packs.buy/orders')</span>
                <span class="packs-resume-card__resume__list__item__price">7,50 €</span>
            </li>
            <li class="packs-resume-card__resume__list__item">
                <span class="packs-resume-card__resume__list__item__pack">@lang('packs.stocks_handling')</span>
                <span class="packs-resume-card__resume__list__item__price">7,50 €</span>
            </li>
            <li class="packs-resume-card__resume__list__item">
                <span class="packs-resume-card__resume__list__item__pack">@lang('packs.payment_handling')</span>
                <span class="packs-resume-card__resume__list__item__price">7,50 €</span>
            </li>
        </ul>
    </div>
    <div class="packs-resume-card__total">
        <h3 class="packs-resume-card__total__title">@lang('titles.total')</h3>
        <div class="packs-resume-card__total__packs-price">
            <p class="packs-resume-card__total__packs-price__packs">4 @lang('titles.packs')</p>
            <p class="packs-resume-card__total__packs-price__price">37,50 €/@lang('titles.months')</p>
        </div>
    </div>
    <x-button size="big">@lang('buttons.finish_payment')</x-button>
</div>
