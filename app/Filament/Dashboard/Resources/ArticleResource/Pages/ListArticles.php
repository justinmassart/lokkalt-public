<?php

namespace App\Filament\Dashboard\Resources\ArticleResource\Pages;

use App\Filament\Dashboard\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArticles extends ListRecords
{
    protected static string $resource = ArticleResource::class;

    public function getTitle(): string
    {
        return ucfirst(__('filament.articles'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
