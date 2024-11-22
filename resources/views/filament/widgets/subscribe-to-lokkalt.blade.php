<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            @lang('filament.welcome_to_lokkalt_dashboard')
        </x-slot>
        <p class="mb-2">
            @lang('filament.choose_sub_desc')
        </p>
        <p class="mb-2">
            @lang('filament.before_choose_sub')
        </p>
        <x-filament::button wire:click="goToSubscriptions">
            @lang('buttons.choose_my_sub')
        </x-filament::button>
    </x-filament::section>
</x-filament-widgets::widget>
