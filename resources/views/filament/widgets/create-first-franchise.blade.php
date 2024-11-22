<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Welcome on the Lokkalt Dashboard !
        </x-slot>
        <p class="mb-2">
            This is the place where you will be able to manage your online franchise(s) and shop(s).
        </p>
        <p class="mb-2">
            Before being able to add articles or anything else - let’s create your first franchise !
        </p>
        <x-filament::button wire:click="goToFranchise">
            Create my first franchise
        </x-filament::button>
    </x-filament::section>
</x-filament-widgets::widget>
