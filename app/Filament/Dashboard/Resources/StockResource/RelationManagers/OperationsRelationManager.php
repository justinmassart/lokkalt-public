<?php

namespace App\Filament\Dashboard\Resources\StockResource\RelationManagers;

use App\Models\StockOperation;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OperationsRelationManager extends RelationManager
{
    protected static string $relationship = 'operations';

    protected $listeners = ['refreshStockOperations' => '$refresh'];

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.stock_history');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                StockOperation::whereStockId($this->ownerRecord->id)->orderBy('created_at', 'DESC')
            )
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('updated_at')
                    ->label(__('filament.date'))
                    ->date('d/m/Y - H:i')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->timezone('Europe/Paris'),
                TextColumn::make('stock_before')
                    ->label(__('filament.before'))
                    ->alignCenter()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('stock_after')
                    ->label(__('filament.after'))
                    ->alignCenter()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('operation')
                    ->label(__('filament.operation'))
                    ->alignCenter()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('user.full_name')
                    ->label(__('filament.made_by'))
                    ->alignEnd()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
            ])
            ->paginated([10, 25, 50, 100])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label(__('filament.between_start')),
                        DatePicker::make('created_until')
                            ->label(__('filament.between_end')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                ViewAction::make()
                    ->modal()
                    ->modalHeading(__('filament.operation'))
                    ->form([
                        Section::make([
                            TextInput::make('updated_at')
                                ->label(__('filament.date'))
                                ->formatStateUsing(static function ($state) {
                                    return Carbon::parse($state)
                                        ->locale(app()->currentLocale())
                                        ->timezone('Europe/Paris')
                                        ->format('d/m/Y - H:i');
                                }),
                            TextInput::make('stock_before')
                                ->label(__('filament.before')),
                            TextInput::make('stock_after')
                                ->label(__('filament.after')),
                            TextInput::make('operation')
                                ->label(__('filament.operation')),
                            Group::make([
                                TextInput::make('full_name')
                                    ->label(__('filament.made_by')),
                            ])->relationship('user'),
                            Textarea::make('comment')
                                ->label(__('filament.comment'))
                                ->autosize(),
                        ])->columns(3),
                    ]),
            ]);
    }
}
