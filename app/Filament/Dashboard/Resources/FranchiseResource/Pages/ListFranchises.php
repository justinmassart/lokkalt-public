<?php

namespace App\Filament\Dashboard\Resources\FranchiseResource\Pages;

use App\Filament\Dashboard\Resources\FranchiseResource;
use App\Filament\Pages\Dashboard;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFranchises extends ListRecords
{
    protected static string $resource = FranchiseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false)
                ->successRedirectUrl(Dashboard::getUrl()),
        ];
    }
}
