<div>
    @php
        $franchises = auth()->user()->franchises;

        $selectedFranchise = session()->get('franchise');
        $selectedShop = session()->get('shop');
    @endphp
    <x-filament::dropdown placement="bottom-start" teleport="true" width="md">
        <x-slot name="trigger">
            <div class="p-2 flex items-center justify-start gap-2">
                @if ($selectedFranchise)
                    <p>{!! $selectedFranchise->name !!}
                        @if ($selectedShop)
                            | {!! $selectedShop->postal_code !!} - {!! $selectedShop->city !!}
                        @endif
                    </p>
                @else
                    @lang('buttons.select_franchise_or_shop')
                @endif
                <x-filament::icon icon="heroicon-c-chevron-down" class="mx-1 h-5 w-5 text-gray-500 dark:text-gray-400" />
            </div>
        </x-slot>

        <x-filament::dropdown.header class="font-semibold" color="gray" icon="heroicon-o-home-modern">
            @lang('buttons.select_franchise_or_shop')
        </x-filament::dropdown.header>

        <x-filament::dropdown.list class="w-64">

            @foreach ($franchises as $franchise)
                <div class="p-2">
                    <a class="flex" href="{!! url('/change-franchise/' . $franchise->id) !!}">{!! $franchise->name !!}</a>
                </div>

                @foreach ($franchise->shops as $shop)
                    <x-filament::dropdown.list.item class="{!! $selectedShop && $selectedShop->slug === $shop->slug ? 'font-semibold' : 'font-normal' !!}" :color="$selectedShop && $selectedShop->slug === $shop->slug ? 'primary' : 'gray'"
                        icon="heroicon-m-chevron-right" :href="url('/change-shop/' . $shop->slug)" tag="a">
                        {!! $shop->name !!} | {!! $shop->postal_code !!} - {!! $shop->city !!}
                    </x-filament::dropdown.list.item>
                @endforeach
            @endforeach

        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
