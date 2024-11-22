<?php

namespace App\Filament\Dashboard\Resources\FranchiseResource\Pages;

use App\Filament\Dashboard\Resources\FranchiseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFranchise extends CreateRecord
{
    protected static string $resource = FranchiseResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $data['verified_at'] = now();

        return $data;
    }

    public function afterCreate(): void
    {
        $franchise = $this->record;

        $franchise->franchiseOwner()->create([
            'user_id' => auth()->user()->id,
        ]);
    }
}
