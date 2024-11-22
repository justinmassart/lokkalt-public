<?php

namespace App\Filament\Dashboard\Widgets;

use App\Filament\Dashboard\Resources\PackResource\Pages\Subscriptions;
use Filament\Widgets\Widget;

class SubscribeToLokkalt extends Widget
{
    protected static string $view = 'filament.widgets.subscribe-to-lokkalt';

    protected int|string|array $columnSpan = 2;

    public static function canView(): bool
    {
        return session()->has('franchise') && !session()->get('franchise')->subscription;
    }

    public function goToSubscriptions()
    {
        return redirect(Subscriptions::getUrl());
    }
}
