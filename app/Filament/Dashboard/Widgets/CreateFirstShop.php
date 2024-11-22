<?php

namespace App\Filament\Dashboard\Widgets;

use App\Filament\Dashboard\Resources\ShopResource\Pages\CreateShop;
use Filament\Widgets\Widget;

class CreateFirstShop extends Widget
{
    protected static string $view = 'filament.widgets.create-first-shop';

    protected int|string|array $columnSpan = 2;

    public static function canView(): bool
    {
        return session()->has('franchise') && !!session()->get('franchise')->subscription && !session()->has('shop') && !session()->get('franchise')->shops()->exists();
    }

    public function goToShop()
    {
        return redirect(CreateShop::getUrl());
    }
}
