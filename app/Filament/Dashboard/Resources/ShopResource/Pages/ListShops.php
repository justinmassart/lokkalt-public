<?php

namespace App\Filament\Dashboard\Resources\ShopResource\Pages;

use App\Filament\Dashboard\Resources\ShopResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShops extends ListRecords
{
    protected static string $resource = ShopResource::class;

    public function getTitle(): string
    {
        return ucfirst(__('filament.shops'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
