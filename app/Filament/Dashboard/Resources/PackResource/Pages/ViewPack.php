<?php

namespace App\Filament\Dashboard\Resources\PackResource\Pages;

use App\Filament\Dashboard\Resources\PackResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPack extends ViewRecord
{
    protected static string $resource = PackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
