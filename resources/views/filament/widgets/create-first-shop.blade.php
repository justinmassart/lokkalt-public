<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            @lang('filament.welcome_to_dashboard')
        </x-slot>
        <p class="mb-2">
            @lang('filament.place_manage_shops')
        </p>
        <p class="mb-2">
            @lang('filament.place_manage_shops_before')
        </p>
        <x-filament::button wire:click="goToShop">
            @lang('buttons.create_first_shop')
        </x-filament::button>
    </x-filament::section>
</x-filament-widgets::widget>
