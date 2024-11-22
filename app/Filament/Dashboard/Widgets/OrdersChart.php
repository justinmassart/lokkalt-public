<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Contracts\Support\Htmlable;

class OrdersChart extends ChartWidget
{
    protected int|string|array $columnSpan = 1;

    public function getHeading(): string|Htmlable|null
    {
        return __('filament.total_orders');
    }

    public static function canView(): bool
    {
        return session()->has('shop') && session()->get('franchise')->hasPack('monitoring');
    }

    public ?string $filter = 'week';

    protected function getFilters(): ?array
    {
        return [
            'today' => __('dates.today'),
            'week' => __('dates.this_week'),
            'month' => __('dates.this_month'),
            'year' => __('dates.this_year'),
        ];
    }

    protected function getData(): array
    {
        $shopID = session()->get('shop')->id;

        $activeFilter = $this->filter;

        $orderData = Trend::query(Order::where('shop_id', $shopID));

        switch ($activeFilter) {
            case 'today':
                $orderData = $orderData->between(
                    start: now()->startOfDay(),
                    end: now()->endOfDay()
                )->perHour();
                break;
            case 'week':
                $orderData = $orderData->between(
                    start: now()->startOfWeek(),
                    end: now()->endOfWeek(),
                )->perDay();
                break;
            case 'month':
                $orderData = $orderData->between(
                    start: now()->startOfMonth(),
                    end: now()->endOfMonth(),
                )->perDay();
                break;
            case 'year':
                $orderData = $orderData->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear(),
                )->perMonth();
                break;
        }

        $orderData = $orderData->count();

        return [
            'datasets' => [
                [
                    'label' => __('filament.total_orders'),
                    'data' => $orderData->map(function (TrendValue $value) {
                        return $value->aggregate;
                    }),
                    'fill' => true,
                    'backgroundColor' => '#375C471A',
                    'borderColor' => '#375C47',
                    'tension' => 0.5,
                ],
            ],
            'labels' => $orderData->map(function (TrendValue $value) use ($activeFilter) {

                switch ($activeFilter) {
                    case 'today':
                        return Carbon::parse($value->date)->locale(app()->currentLocale())->timezone('Europe/Paris')->isoFormat('H:mm');
                        break;
                    case 'week':
                        return Carbon::parse($value->date)->locale(app()->currentLocale())->timezone('Europe/Paris')->isoFormat('dddd');
                        break;
                    case 'month':
                        return Carbon::parse($value->date)->locale(app()->currentLocale())->timezone('Europe/Paris')->isoFormat('DD/MM');
                        break;
                    case 'year':
                        return Carbon::parse($value->date)->locale(app()->currentLocale())->timezone('Europe/Paris')->isoFormat('MMMM');
                        break;
                }
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
