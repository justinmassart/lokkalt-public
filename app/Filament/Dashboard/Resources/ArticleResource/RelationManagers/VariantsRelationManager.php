<?php

namespace App\Filament\Dashboard\Resources\ArticleResource\RelationManagers;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('is_visible')
                    ->label('Visible ?')
                    ->onIcon('heroicon-c-check')
                    ->onColor('success')
                    ->offIcon('heroicon-c-x-mark')
                    ->offColor('danger')
                    ->columnSpanFull()
                    ->required(),
                TextInput::make('name')
                    ->label('Variant Name')
                    ->required()
                    ->live(onBlur: true)
                    ->maxLength(255)
                    ->afterStateUpdated(function (Set $set, RelationManager $livewire, $state) {
                        $articleSlug = $livewire->getOwnerRecord()->slug;
                        $newStateSlug = str()->slug($state);
                        $slug = str()->slug($articleSlug.'-'.$newStateSlug);
                        $set('slug', $slug);
                    }),
                TextInput::make('slug')
                    ->default(static function (RelationManager $livewire) {
                        return $livewire->getOwnerRecord()->slug;
                    })
                    ->disabled(),
                Textarea::make('description')
                    ->label('Variant Description')
                    ->hint('Max 325 characters')
                    ->minLength(5)
                    ->maxLength(325)
                    ->rows(3)
                    ->autosize()
                    ->columnSpanFull(),
                Repeater::make('prices')
                    ->relationship('prices')
                    ->required()
                    ->label('Price')
                    ->addActionLabel('Add a price')
                    ->reorderable(false)
                    ->addable(static function ($state) {
                        return count($state) === 3 ? false : true;
                    })
                    ->columns(2)
                    ->grid(4)
                    ->columnSpanFull()
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                        if (isset($data['price'])) {
                            $data['price'] = str_replace(',', '.', $data['price']);
                        }

                        return $data;
                    })
                    ->schema([
                        TextInput::make('price')
                            ->required()
                            ->inputMode('decimal')
                            ->minValue(0)
                            ->maxValue(10000)
                            ->columnSpanFull()
                            ->numeric()
                            ->live(onBlur: true),
                        Select::make('currency')
                            ->required()
                            ->columnSpanFull()
                            ->options([
                                'EUR' => 'EUR',
                                'GBP' => 'GBP',
                                'USD' => 'USD',
                            ])
                            ->in([
                                'EUR' => 'EUR',
                                'GBP' => 'GBP',
                                'USD' => 'USD',
                            ])
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                    ]),
                KeyValue::make('details')
                    ->label('Variant Details')
                    ->hint('You can register here the details about this variant.')
                    ->keyLabel('Features')
                    ->keyPlaceholder('Weight, Color, Size, Liters, ...')
                    ->valueLabel('Value')
                    ->valuePlaceholder('250g, blue, M, 3.5L')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->weight(FontWeight::SemiBold)
                    ->searchable(),
                TextColumn::make('description')
                    ->toggleable()
                    ->wrap(),
                IconColumn::make('is_visible')
                    ->label('Visible ?')
                    ->toggleable()
                    ->alignCenter(true)
                    ->icon(fn (bool $state): string => match ($state) {
                        true => 'heroicon-o-check-circle',
                        false => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn (bool $state): string => match ($state) {
                        true => 'success',
                        false => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('prices')
                    ->getStateUsing(static function (?Model $record) {
                        $prices = $record->prices->sortBy('currency');
                        $displayPrices = [];
                        foreach ($prices as $price) {
                            $displayPrices[] = $price->price.' '.$price->currency;
                        }

                        return new HtmlString(implode('<br>', $displayPrices));
                    })
                    ->toggleable()
                    ->searchable(),
                ImageColumn::make('images.url')
                    ->disk('s3')
                    ->defaultImageUrl(static function (?Model $record) {
                        if ($record && $record->images()->count() === 0) {
                            return '';
                        }

                        return Storage::disk('s3')->temporaryUrl('web/small/'.$record->url, now()->addMinutes(30));
                    })
                    ->toggleable()
                    ->height(static function (?Model $record) {
                        if ($record && $record->images()->count() === 0) {
                            return 0;
                        }

                        return 75;
                    })
                    ->width(static function (?Model $record) {
                        if ($record && $record->images()->count() === 0) {
                            return 0;
                        }

                        return 75;
                    })
                    ->extraImgAttributes(['loading' => 'lazy']),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $ownerSlug = $this->ownerRecord->slug;
                        $data['slug'] = str()->slug($ownerSlug.'-'.$data['name']);
                        count($data['details']) === 0 ? $data['details'] = null : null;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $ownerSlug = $this->ownerRecord->slug;
                        $data['slug'] = str()->slug($ownerSlug.'-'.$data['name']);
                        count($data['details']) === 0 ? $data['details'] = null : null;

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
