<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.my_orders')))
    <section class="orders">
        <div class="orders__header">
            <x-main-title icon="cart">@lang('titles.my_orders')</x-main-title>
        </div>
        <livewire:orders.orders-list />
    </section>
</x-layouts.app>
