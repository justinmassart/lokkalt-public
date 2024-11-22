<?php

namespace App\Filament\Dashboard\Resources\FranchiseResource\RelationManagers;

use App\Filament\Dashboard\Resources\ShopResource;
use App\Models\Shop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShopsRelationManager extends RelationManager
{
    protected static string $relationship = 'shops';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('is_active')
                    ->label(__('filament.is_active?'))
                    ->toggleable()
                    ->getStateUsing(static function (Model $record) {
                        return $record->is_active == true ? 'Active' : 'Inactive';
                    })
                    ->color(
                        static function ($state) {
                            return $state === 'Active' ? 'success' : 'danger';
                        }
                    ),
                TextColumn::make('email')
                    ->label(__('filament.email'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('filament.phone_number'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('address')
                    ->label(__('filament.address'))
                    ->wrap()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('VAT')
                    ->label(__('filament.vat'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('country')
                    ->label(__('filament.country'))
                    ->size(TextColumnSize::Large)
                    ->getStateUsing(static function (Model $record) {
                        return $record->flag();
                    })
                    ->toggleable()
                    ->searchable(),
            ])
            ->actions([
                ViewAction::make()
                    ->url(fn (Shop $record): string => ShopResource::getUrl('view', [$record->slug])),
            ]);
    }
}
