<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.my_cart')))
    <section class="cart">
        <div class="cart__header">
            <x-main-title icon="cart">@lang('titles.my_cart')</x-main-title>
            {{-- <x-button event="emptyCart" style="outlined">@lang('buttons.empty_cart')</x-button> --}}
        </div>
        <livewire:cart.cart />
    </section>
</x-layouts.app>
