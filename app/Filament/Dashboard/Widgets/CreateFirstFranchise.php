<?php

namespace App\Filament\Dashboard\Widgets;

use App\Filament\Dashboard\Resources\FranchiseResource\Pages\CreateFranchise;
use Filament\Widgets\Widget;

class CreateFirstFranchise extends Widget
{
    protected static string $view = 'filament.widgets.create-first-franchise';

    protected int|string|array $columnSpan = 2;

    protected static ?string $heading = 'Create your first franchise !';

    public static function canView(): bool
    {
        return !session()->has('franchise') && !auth()->user()->franchises()->exists();
    }

    public function goToFranchise()
    {
        return redirect(CreateFranchise::getUrl());
    }
}
