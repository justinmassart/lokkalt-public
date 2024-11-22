<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.my_notifications')))
    <section class="notifications">
        <x-main-title icon="notifications">@lang('titles.my_notifications')</x-main-title>
        <div class="notifications__content">
            <div class="notifications__content__filters">
                <x-button style="outlined">@lang('buttons.filter')</x-button>
                <x-button style="outlined">@lang('buttons.sort')</x-button>
            </div>
            <div class="notifications__content__list">
                <div class="notifications__content__list__left">
                    <x-notifications.notification />
                    <x-notifications.notification />
                    <x-notifications.notification />
                </div>
                <div class="notifications__content__list__right">
                    <x-notifications.notification />
                    <x-notifications.notification />
                    <x-notifications.notification />
                </div>
            </div>
            <div class="pagination">
                <div class="pagination__item chevron-left"><x-icons name="chevron" /></div>
                <div class="pagination__item active">1</div>
                <div class="pagination__item">2</div>
                <div class="pagination__item">3</div>
                <div class="pagination__item chevron-right"><x-icons name="chevron" /></div>
            </div>
        </div>
    </section>
</x-layouts.app>
