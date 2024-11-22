<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $pollingInterval = '10s';

    public static function canView(): bool
    {
        return session()->has('shop');
    }

    protected function getStats(): array
    {
        $shopID = session()->get('shop')->id;

        $newOrdersCount = Order::where('shop_id', $shopID)
            ->where('status', '!=', 'available')
            ->where('status', '!=', 'delivered')
            ->where('status', '!=', 'refunded')
            ->count();

        $availableOrdersCount = Order::where('shop_id', $shopID)
            ->where('status', '==', 'available')
            ->count();

        $totalOrdersCount = Order::where('shop_id', $shopID)
            ->where('status', '==', 'delivered')
            ->count();

        $currentDate = Carbon::now();

        $firstDayOfCurrentMonth = $currentDate->firstOfMonth();

        $firstDayOfPreviousMonth = $currentDate->subMonthNoOverflow()->firstOfMonth();
        $lastDayOfPreviousMonth = $firstDayOfPreviousMonth->copy()->endOfMonth();

        $newCustomersCount = Order::where('shop_id', $shopID)
            ->where('created_at', '>=', $firstDayOfCurrentMonth)
            ->distinct('user_id')
            ->count('user_id');

        $totalCustomersPastMonth = Order::where('shop_id', $shopID)
            ->whereBetween('created_at', [$firstDayOfPreviousMonth, $lastDayOfPreviousMonth])
            ->distinct('user_id')
            ->count('user_id');

        $totalCustomersPastMonth = $totalCustomersPastMonth === 0 ? 1 : $totalCustomersPastMonth;

        return [
            Stat::make('New orders', $newOrdersCount)
                ->label(__('filament.new_orders'))
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'href' => '/orders',
                    'wire:navigate' => '',
                ]),
            Stat::make('Available orders', $availableOrdersCount)
                ->label(__('filament.available_orders'))
                ->icon('heroicon-o-check-circle')
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'href' => '/orders?activeTab=Available',
                    'wire:navigate' => '',
                ]),
            Stat::make('Total orders', $totalOrdersCount)
                ->label(__('filament.total_orders'))
                ->icon('heroicon-o-squares-2x2')
                ->description(__('filament.only_delivered_ones')),
            Stat::make('New Customers', $newCustomersCount)
                ->label(__('filament.new_customers'))
                ->icon('heroicon-o-user-group')
                ->description('+' . $totalCustomersPastMonth . ' ' . __('filament.new_customer_compaired_to_last_month')),
        ];
    }
}
