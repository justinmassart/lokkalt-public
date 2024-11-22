<?php

namespace App\Filament\Dashboard\Resources\OrderResource\RelationManagers;

use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament.articles_in_order');
    }

    protected $listeners = ['refreshOrderStatus' => '$refresh'];

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Stack::make([
                    TextColumn::make('shopArticle.article.name')
                        ->label(__('filament.article'))
                        ->prefix(__('filament.article') . ': '),
                    TextColumn::make('shopArticle.variant.name')
                        ->label(__('filament.variant'))
                        ->prefix(__('filament.variant') . ': '),
                    TextColumn::make('price')
                        ->label(__('filament.price'))
                        ->prefix(__('filament.price_per_unit') . ': ')
                        ->formatStateUsing(static function ($state) {
                            return $state . '€';
                        }),
                    TextColumn::make('quantity')
                        ->label(__('filament.quantity'))
                        ->prefix(__('filament.quantity') . ': '),
                    TextColumn::make('order.sub_total')
                        ->label(__('titles.total'))
                        ->prefix(__('filament.total') . ': ')
                        ->getStateUsing(static function (Model $record) {
                            return $record->price * $record->quantity . '€';
                        }),
                    TextColumn::make('status')
                        ->label(__('filament.status'))
                        ->badge()
                        ->getStateUsing(static function (Model $record) {
                            return __('titles.' . $record->status);
                        })
                        ->hidden(static function (RelationManager $livewire) {
                            return $livewire->ownerRecord->status === 'available' || $livewire->ownerRecord->status === 'delivered' || $livewire->ownerRecord->status === 'refunded';
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
                        }),
                ]),
            ])
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
            ])
            ->paginated(false)
            ->actions([
                Action::make('start')
                    ->label(__('filament.start_prep'))
                    ->icon('heroicon-o-play-circle')
                    ->color('warning')
                    ->button()
                    ->action(static function (Model $record) {
                        $record->update([
                            'status' => 'started',
                        ]);

                        $record->order->update([
                            'status' => 'started',
                        ]);
                    })
                    ->after(function (Component $livewire) {
                        $livewire->dispatch('refreshOrderStatus');
                    })
                    ->disabled(static function (Model $record) {
                        return $record->status === 'started';
                    })
                    ->hidden(static function (RelationManager $livewire) {
                        return $livewire->ownerRecord->status === 'available' || $livewire->ownerRecord->status === 'delivered' || $livewire->ownerRecord->status === 'refunded';
                    }),
                Action::make('ready')
                    ->label(__('filament.available_prep'))
                    ->icon('heroicon-o-check-circle')
                    ->color('primary')
                    ->button()
                    ->action(static function (Model $record, RelationManager $livewire) {
                        $record->update([
                            'status' => 'ready',
                        ]);

                        $record->order->update([
                            'status' => 'started',
                        ]);

                        $order = $livewire->ownerRecord;
                        $orderHasEveryItemReady = !$order->items()->where('status', '!=', 'ready')->exists();
                        if ($orderHasEveryItemReady) {
                            Notification::make()
                                ->title('The order is ready to be mark as "Available"')
                                ->icon('heroicon-o-check-circle')
                                ->iconColor('primary')
                                ->send();
                        }
                    })
                    ->after(function (Component $livewire) {
                        $livewire->dispatch('refreshOrderStatus');
                    })
                    ->disabled(static function (Model $record) {
                        return $record->status === 'ready';
                    })
                    ->hidden(static function (RelationManager $livewire) {
                        return $livewire->ownerRecord->status === 'available' || $livewire->ownerRecord->status === 'delivered' || $livewire->ownerRecord->status === 'refunded';
                    }),
            ]);
    }
}
