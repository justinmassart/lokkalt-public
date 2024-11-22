<?php

namespace App\Filament\Dashboard\Resources\PackResource\Pages;

use App\Filament\Dashboard\Resources\PackResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPacks extends ListRecords
{
    protected static string $resource = PackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false),
        ];
    }
}
