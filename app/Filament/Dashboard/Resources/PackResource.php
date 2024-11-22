<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\PackResource\Pages;
use App\Models\Pack;
use Filament\Resources\Resource;

class PackResource extends Resource
{
    protected static ?string $model = Pack::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?string $activeNavigationIcon = 'heroicon-s-squares-plus';

    public static function getPages(): array
    {
        return [
            'index' => Pages\Subscriptions::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament.subscription');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.subscriptions');
    }
}
