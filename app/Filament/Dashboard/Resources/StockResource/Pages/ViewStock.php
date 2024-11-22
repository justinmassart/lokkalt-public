<?php

namespace App\Filament\Dashboard\Resources\StockResource\Pages;

use App\Filament\Dashboard\Resources\StockResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewStock extends ViewRecord
{
    protected static string $resource = StockResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('filament.stock_of').' '.$this->record->shopArticle->article->name.' - '.$this->record->shopArticle->variant->name.' #'.$this->record->shopArticle->variant->reference;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['comment'] = $this->record->operations()->latest()->first() ? $this->record->operations()->latest()->first()->comment : '';

        return $data;
    }
}
