<div class="modal" x-init="$watch('filter_modal', value => document.body.classList.toggle('no-scroll', value))" @click.away="filter_modal = false">
    <div class="modal__header">
        <div class="modal__header__left">
            <x-icons name="filter" />
            <h3 class="modal__header__left__title">@lang('titles.filter_results')</h3>
        </div>
        <div class="modal__header__right" @click.prevent="filter_modal= false">
            <x-icons name="cross" />
        </div>
    </div>
    <div class="modal__content">
        <div class="modal__content__filter-container filter categories-filter" x-data="{ open: false }"
            :class="{ 'expanded': open }">
            <div class="modal__content__filter-container__header filter__header" @click.prevent="open = !open">
                <p class="modal__content__filter-container__header__title filter__header__title">Catégories d’article
                </p>
                <x-icons name="chevron" />
            </div>
            <form method="GET" class="modal__content__filter-container__list filter__content filter__checkboxes">
                <div class="modal__content__filter-container__list__item filter__checkboxes__item">
                    <input type="checkbox" id="category-1" name="category-1" value="fromages">
                    <span class="checkmark"></span>
                    <label for="category-1">Fromages</label>
                </div>
                <div class="modal__content__filter-container__list__item filter__checkboxes__item">
                    <input type="checkbox" id="category-2" name="category-2" value="fruits">
                    <span class="checkmark"></span>
                    <label for="category-2">Fruits</label>
                </div>
                <div class="modal__content__filter-container__list__item filter__checkboxes__item">
                    <input type="checkbox" id="category-3" name="category-3" value="legumes">
                    <span class="checkmark"></span>
                    <label for="category-3">Légumes</label>
                </div>
                <div class="modal__content__filter-container__list__item filter__checkboxes__item">
                    <input type="checkbox" id="category-4" name="category-4" value="patisserie">
                    <span class="checkmark"></span>
                    <label for="category-4">Pâtisserie</label>
                </div>
                <div class="modal__content__filter-container__list__item filter__checkboxes__item">
                    <input type="checkbox" id="category-5" name="category-5" value="poisson">
                    <span class="checkmark"></span>
                    <label for="category-5">Poisson</label>
                </div>
                <div class="modal__content__filter-container__list__item filter__checkboxes__item">
                    <input type="checkbox" id="category-6" name="category-6" value="sec">
                    <span class="checkmark"></span>
                    <label for="category-6">Sec</label>
                </div>
                <div class="modal__content__filter-container__list__item filter__checkboxes__item">
                    <input type="checkbox" id="category-7" name="category-7" value="viandes">
                    <span class="checkmark"></span>
                    <label for="category-7">Viandes</label>
                </div>
                <div class="modal__content__filter-container__list__item filter__checkboxes__item">
                    <input type="checkbox" id="category-8" name="category-8" value="volaille">
                    <span class="checkmark"></span>
                    <label for="category-8">Volaille</label>
                </div>
            </form>
        </div>
        <div class="modal__content__filter-container filter price-filter" x-data="{ open: false }"
            :class="{ 'expanded': open }">
            <div class="modal__content__filter-container__header filter__header" @click.prevent="open = !open">
                <p class="modal__content__filter-container__header__title filter__header__title">Prix</p>
                <x-icons name="chevron" />
            </div>
            <div class="modal__content__filter-container__content filter__content">
                <form class="modal__content__filter-container__content__for form filter__form" x-data="{ price: 0 }">
                    <div class="modal__content__filter-container__content__form__field form__field">
                        <label class="modal__content__filter-container__content__form__label form__field__label"
                            for="category-filter-price">Prix</label>
                        <input class="modal__content__filter-container__content__form__input form__field__input"
                            type="range" name="price" id="category-filter-price" min="0" max="1000"
                            step="0.01" x-bind="price" @change="console.log(price)">
                    </div>
                    <x-button>Valider</x-button>
                </form>
            </div>
        </div>
        <div class="modal__content__filter-container filter location-filter" x-data="{ open: false }"
            :class="{ 'expanded': open }">
            <div class="modal__content__filter-container__header filter__header" @click.prevent="open = !open">
                <p class="modal__content__filter-container__header__title filter__header__title">Localité</p>
                <x-icons name="chevron" />
            </div>
            <div class="modal__content__filter-container__content filter__content">
                <form class="modal__content__filter-container__content__for form filter__form">
                    <div class="modal__content__filter-container__content__form__field form__field">
                        <label class="modal__content__filter-container__content__form__label form__field__label"
                            for="category-filter-address">Adresse / Lieu / Code postal</label>
                        <input class="modal__content__filter-container__content__form__input form__field__input"
                            type="text" name="shop_notifications" id="category-filter-address"
                            placeholder="{!! __('inputs.email_placeholder') !!}">
                    </div>
                    <x-button>Valider</x-button>
                </form>
            </div>
        </div>
        <div class="modal__content__filter-container filter shop-size-filter" x-data="{ open: false }"
            :class="{ 'expanded': open }">
            <div class="modal__content__filter-container__header filter__header" @click.prevent="open = !open">
                <p class="modal__content__filter-container__header__title filter__header__title">Préférer les petits
                    commerces ?</p>
                <x-icons name="chevron" />
            </div>
            <div class="modal__content__filter-container__content filter__content">
                <form class="form filter__form">
                    <div class="modal__content__filter-container__content__item form__item">
                        <input type="radio" id="category-1" name="smaller-shops" value="true">
                        <span class="checkmark"></span>
                        <label for="true">Oui, préférer les petits commerces/commerçant•e•s</label>
                    </div>
                    <div class="modal__content__filter-container__content__item form__item">
                        <input type="radio" id="category-1" name="smaller-shops" value="false">
                        <span class="checkmark"></span>
                        <label for="false">Non, voir tous types de commerces/commerçant•e•s</label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
