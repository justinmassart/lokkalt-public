<?php

namespace App\Filament\Dashboard\Resources\ArticleResource\Pages;

use App\Filament\Dashboard\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewArticle extends ViewRecord
{
    protected static string $resource = ArticleResource::class;

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
}
