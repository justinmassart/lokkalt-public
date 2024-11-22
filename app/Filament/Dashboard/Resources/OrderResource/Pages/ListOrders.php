<?php

namespace App\Filament\Dashboard\Resources\OrderResource\Pages;

use App\Filament\Dashboard\Resources\OrderResource;
use App\Models\Order;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    public function getTabs(): array
    {
        $shopID = session()->get('shop')->id;

        $newCount = Order::where('shop_id', $shopID)
            ->where('status', '!=', 'available')
            ->where('status', '!=', 'delivered')
            ->where('status', '!=', 'refunded')
            ->count();

        $availableCount = Order::where('shop_id', $shopID)->where('status', 'available')->count();

        return [
            __('filament.new') => Tab::make()
                ->badge($newCount)
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('order', function (Builder $query) {
                        $query->where('status', '!=', 'available')
                            ->where('status', '!=', 'delivered')
                            ->where('status', '!=', 'refunded');
                    });
                }),
            __('filament.available') => Tab::make()
                ->badge($availableCount)
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('order', function (Builder $query) {
                        $query->where('status', 'available');
                    });
                }),
            __('filament.delivered') => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('order', function (Builder $query) {
                        $query->where('status', 'delivered');
                    });
                }),
            __('filament.refunded') => Tab::make()
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('order', function (Builder $query) {
                        $query->where('status', 'refunded');
                    });
                }),
        ];
    }
}
