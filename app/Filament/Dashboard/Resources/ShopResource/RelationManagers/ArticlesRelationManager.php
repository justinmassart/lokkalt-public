<?php

namespace App\Filament\Dashboard\Resources\ShopResource\RelationManagers;

use App\Filament\Dashboard\Resources\ArticleResource;
use App\Models\Article;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ArticlesRelationManager extends RelationManager
{
    protected static string $relationship = 'articles';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.articles');
    }

    public function table(Table $table): Table
    {
        return ArticleResource::table($table)
            ->query(
                Article::whereHas('shopArticles', function ($query) {
                    $query->where('shop_id', $this->ownerRecord->id);
                })
                    ->with([
                        'variants',
                        'category',
                        'sub_category',
                    ])
                    ->withAvg('scores', 'score')
            )
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                SelectFilter::make('sub_category_id')
                    ->label('Sub Category')
                    ->relationship('sub_category', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->paginated(false);
    }
}
