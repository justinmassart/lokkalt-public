<?php

namespace App\Filament\Dashboard\Resources\ShopResource\Pages;

use App\Filament\Dashboard\Resources\ShopResource;
use App\Models\Franchise;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditShop extends EditRecord
{
    protected static string $resource = ShopResource::class;

    protected ?bool $hasDatabaseTransactions = true;

    public function getTitle(): string|Htmlable
    {
        return 'Edit ' . $this->data['name'];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->successRedirectUrl('/'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['franchise'] = $this->record->franchise->id;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $franchise = Franchise::whereId($data['franchise'])->first();

        $data['slug'] = str()->slug($data['country'] . ' ' . $data['postal_code'] . ' ' . str()->slug($franchise->slug));

        unset($data['franchise']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
