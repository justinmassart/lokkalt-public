<?php

namespace App\Filament\Dashboard\Resources\ShopResource\Pages;

use App\Filament\Dashboard\Resources\ShopResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewShop extends ViewRecord
{
    protected static string $resource = ShopResource::class;

    public function getTitle(): string|Htmlable
    {
        return $this->data['name'];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['franchise'] = $this->record->franchise->id;

        return $data;
    }
}
