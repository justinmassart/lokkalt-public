<?php

namespace App\Filament\Dashboard\Resources\FranchiseResource\Pages;

use App\Filament\Dashboard\Resources\FranchiseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewFranchise extends ViewRecord
{
    protected static string $resource = FranchiseResource::class;

    public function getTitle(): string|Htmlable
    {
        return $this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
