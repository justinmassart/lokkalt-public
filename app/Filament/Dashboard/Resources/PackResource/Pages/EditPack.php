<?php

namespace App\Filament\Dashboard\Resources\PackResource\Pages;

use App\Filament\Dashboard\Resources\PackResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPack extends EditRecord
{
    protected static string $resource = PackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
