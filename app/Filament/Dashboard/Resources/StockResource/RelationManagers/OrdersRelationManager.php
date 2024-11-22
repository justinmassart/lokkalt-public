<?php

namespace App\Filament\Dashboard\Resources\StockResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.orders_history');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->date('d/m/Y - H:i')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)
                            ->locale(app()->currentLocale())
                            ->timezone('Europe/Paris')
                            ->format('d/m/Y - H:i');
                    }),
                TextColumn::make('order.reference')
                    ->label(__('filament.order_reference'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('order.status')
                    ->label(__('filament.order_status'))
                    ->alignCenter()
                    ->badge()
                    ->getStateUsing(static function (Model $record) {
                        return __('titles.' . $record->status);
                    })
                    ->icon(static function (Model $record) {
                        switch ($record->status) {
                            case 'waiting':
                                return 'heroicon-o-clock';
                                break;
                            case 'started':
                                return 'heroicon-o-play-circle';
                                break;
                            case 'ready':
                                return 'heroicon-o-check-circle';
                                break;
                            case 'refunded':
                                return 'heroicon-o-receipt-refund';
                                break;
                        }
                    })
                    ->color(static function (Model $record) {
                        switch ($record->status) {
                            case 'waiting':
                                return 'danger';
                                break;
                            case 'started':
                                return 'warning';
                                break;
                            case 'ready':
                                return 'primary';
                                break;
                            case 'refunded':
                                return 'danger';
                                break;
                        }
                    })
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('quantity')
                    ->label(__('filament.quantity'))
                    ->alignCenter()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('price')
                    ->label(__('filament.price'))
                    ->alignCenter()
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->formatStateUsing(static function ($state) {
                        return number_format($state, 2, ',', ' ') . '€';
                    }),
                TextColumn::make('total')
                    ->label(__('filament.total'))
                    ->alignCenter()
                    ->sortable()
                    ->toggleable()
                    ->getStateUsing(static function (Model $record) {
                        return number_format($record->price * $record->quantity, 2, ',', ' ') . '€';
                    }),
                TextColumn::make('has_been_refunded')
                    ->label(__('filament.has_been_refunded?'))
                    ->alignCenter()
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->formatStateUsing(static function ($state) {
                        if ($state == false) {
                            return __('filament.no');
                        } else {
                            return __('filament.yes');
                        }
                    }),
                TextColumn::make('updated_at')
                    ->label(__('filament.updated_at'))
                    ->date('d/m/Y - H:i')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->formatStateUsing(function ($state) {
                        return Carbon::parse($state)
                            ->locale(app()->currentLocale())
                            ->timezone('Europe/Paris')
                            ->format('d/m/Y - H:i');
                    }),
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
            ]);
    }
}
