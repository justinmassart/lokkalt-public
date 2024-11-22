<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\OrderResource\Pages;
use App\Filament\Dashboard\Resources\OrderResource\RelationManagers\ItemsRelationManager;
use App\Mail\OrderAvailableMail;
use App\Mail\OrderContactMail;
use App\Mail\OrderDeliveredMail;
use App\Mail\OrderRefundedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Stripe\StripeClient;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $activeNavigationIcon = 'heroicon-s-shopping-cart';

    // TODO: display refunded articles - for now it only displays refunded if all articles have been refunded

    public static function getNavigationBadge(): ?string
    {
        $shopID = session()->get('shop')->id;

        $count = Order::where('shop_id', $shopID)
            ->where('status', '!=', 'available')
            ->where('status', '!=', 'delivered')
            ->where('status', '!=', 'refunded')
            ->count();

        return $count > 0 ? $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return __('filament.number_of_orders');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament.quick_actions_section'))
                    ->schema([
                        ToggleButtons::make('status')
                            ->label(__('filament.order_status'))
                            ->inline()
                            ->options([
                                'waiting' => 'Waiting',
                                'started' => 'Started',
                                'available' => 'Available',
                                'delivered' => 'Delivered',
                                'refunded' => 'Refunded',
                            ])
                            ->colors([
                                'waiting' => 'danger',
                                'started' => 'warning',
                                'available' => 'primary',
                                'delivered' => 'primary',
                                'refunded' => 'info',
                            ])
                            ->icons([
                                'waiting' => 'heroicon-o-clock',
                                'started' => 'heroicon-o-play',
                                'available' => 'heroicon-o-check-circle',
                                'delivered' => 'heroicon-o-archive-box-arrow-down',
                                'refunded' => 'heroicon-o-receipt-refund',
                            ])
                            ->disabled(),
                        Actions::make([
                            Action::make('make_available')
                                ->label(__('filament.make_order_available'))
                                ->icon('heroicon-o-check-circle')
                                ->color('primary')
                                ->button()
                                ->requiresConfirmation()
                                ->modalDescription(static function (Model $record) {
                                    $string = '';

                                    if ($record->items->where('status', '!=', 'ready')->count() > 0) {
                                        $string = 'Êtes-vous sûr de vouloir faire cela ? Un ou plusieurs articles ne sont pas marqués comme "prêt" :';
                                    } else {
                                        $string = 'Êtes-vous sûr de vouloir faire cela ?';
                                    }

                                    return new HtmlString($string);
                                })
                                ->modalContent(static function (Model $record) {
                                    $items = $record->items->where('status', '!=', 'ready');

                                    if ($items->count() === 0) {
                                        return;
                                    }

                                    $string = '';
                                    foreach ($items as $item) {
                                        $string .= '<br>' . '- ' . $item->shopArticle->article->name . ' | ' . $item->shopArticle->variant->name . ' (' . __('titles.' . $item->status) . ')';
                                    }

                                    return new HtmlString($string);
                                })
                                ->action(static function (Model $record, Component $livewire) {
                                    $record->update([
                                        'status' => 'available',
                                    ]);

                                    $livewire->dispatch('refreshOrderStatus');

                                    Mail::to($record->user->email)
                                        ->later(now()->addMinutes(5), new OrderAvailableMail($record->user, $record));

                                    Notification::make()
                                        ->title('The client will be informed of the availability of the order in a few minutes.')
                                        ->icon('heroicon-o-check-circle')
                                        ->iconColor('primary')
                                        ->send();

                                    $livewire->dispatch('refreshOrderStatus');
                                })
                                ->hidden(static function (Model $record) {
                                    return $record->status === 'delivered' || $record->status === 'refunded' || $record->status === 'available';
                                }),
                            Action::make('not_make_available')
                                ->label(__('filament.make_order_not_available'))
                                ->icon('heroicon-o-x-circle')
                                ->color('warning')
                                ->button()
                                ->requiresConfirmation()
                                ->action(static function (Model $record, Component $livewire) {
                                    $record->update([
                                        'status' => 'started',
                                    ]);

                                    $livewire->dispatch('refreshOrderStatus');
                                })
                                ->hidden(static function (Model $record) {
                                    return $record->status === 'delivered' || $record->status === 'refunded' || $record->status !== 'available';
                                }),
                            Action::make('mark_delivered')
                                ->label(__('filament.mark_as_delivered'))
                                ->icon('heroicon-o-archive-box-arrow-down')
                                ->color('info')
                                ->button()
                                ->requiresConfirmation()
                                ->action(static function (Model $record, Component $livewire) {
                                    $record->update([
                                        'status' => 'delivered',
                                    ]);

                                    Mail::to($record->user->email)
                                        ->queue(new OrderDeliveredMail($record->user, $record));

                                    $livewire->dispatch('refreshOrderStatus');
                                })
                                ->hidden(static function (Model $record) {
                                    return $record->status !== 'available';
                                }),
                        ])->fullWidth()
                            ->hidden(static function (Model $record) {
                                return $record->status === 'delivered' || $record->status === 'refunded';
                            }),
                        Actions::make([
                            Action::make('contact')
                                ->label(__('filament.contact_client'))
                                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                                ->color('gray')
                                ->button()
                                ->form([
                                    Select::make('reason')
                                        ->label(__('filament.reason'))
                                        ->native(false)
                                        ->options([
                                            'stock' => 'About stocks',
                                        ]),
                                    Textarea::make('message')
                                        ->label(__('filament.message')),
                                ])
                                ->action(static function (Model $record, array $data) {
                                    Mail::to($record->user->email)
                                        ->queue(new OrderContactMail($record->user, $record, $data['reason'], $data['message']));

                                    Notification::make()
                                        ->title('The email has been sent to the client.')
                                        ->icon('heroicon-o-check-circle')
                                        ->iconColor('primary')
                                        ->send();
                                }),
                            Action::make('refund')
                                ->label(__('filament.refund'))
                                ->icon('heroicon-o-currency-euro')
                                ->color('danger')
                                ->button()
                                ->requiresConfirmation()
                                ->form([
                                    Select::make('articles')
                                        ->native(false)
                                        ->multiple()
                                        ->required()
                                        ->options(static function (Model $record) {
                                            $orderItems = $record->items->where('has_been_refunded', false);

                                            $options = $orderItems->mapWithKeys(function ($item) {
                                                return [$item->shopArticle->id => $item->shopArticle->article->name . ' - ' . $item->shopArticle->variant->name];
                                            });

                                            return $options;
                                        }),
                                    Textarea::make('reason')
                                        ->label(__('filament.reason'))
                                        ->required(),
                                ])
                                ->action(static function (Model $record, array $data, Component $livewire) {
                                    $refundedShopArticles = OrderItem::whereOrderId($record->id)->whereIn('shop_article_id', $data['articles'])->get();

                                    $refundedAmount = 0;
                                    foreach ($refundedShopArticles as $orderItem) {
                                        $refundedAmount += $orderItem->price * $orderItem->quantity;

                                        $oldStockQuantity = $orderItem->shopArticle->stock->quantity;
                                        $newStockQuantity = $oldStockQuantity + $orderItem->quantity;
                                        $newStockStatus = '';

                                        if ($newStockQuantity >= 5) {
                                            $newStockStatus = 'in';
                                        } elseif ($newStockQuantity < 5 && $newStockQuantity > 0) {
                                            $newStockStatus = 'limited';
                                        } elseif ($newStockQuantity === 0) {
                                            $newStockStatus = 'out';
                                        }

                                        $orderItem->shopArticle->stock()->update([
                                            'quantity' => $newStockQuantity,
                                            'status' => $newStockStatus,
                                        ]);
                                    }
                                    $refundedAmount =
                                        $refundedAmount +
                                        ($refundedAmount * config('services.stripe.fee_percentage')) / 100;

                                    $alreadyHasRefunds = Order::wherePaymentId($record->payment_id)->whereHasBeenRefunded(true)->exists();

                                    if (!$alreadyHasRefunds) {
                                        $refundedAmount += config('services.stripe.fee_fixed');
                                    }

                                    $refundedAmount = round($refundedAmount, 2) * 100;

                                    $stripe = new StripeClient(config('services.stripe.secret'));
                                    $refund = $stripe->refunds->create([
                                        'payment_intent' => $record->payment_id,
                                        'amount' => $refundedAmount,
                                    ]);

                                    // TODO: check for pending refunds & check to see if 0,35€ is taxed to seller for refunding

                                    if (!$refund) {
                                        Notification::make()
                                            ->title('Whoops... It seems that the refund coudln’t be completed.')
                                            ->icon('heroicon-o-x-circle')
                                            ->iconColor('danger')
                                            ->send();

                                        return;
                                    }

                                    $record->update([
                                        'has_been_refunded' => true,
                                        'status' => 'refunded',
                                    ]);

                                    foreach ($refundedShopArticles as $orderItem) {
                                        $orderItem->update([
                                            'has_been_refunded' => true,
                                            'status' => 'refunded',
                                        ]);
                                    }

                                    $livewire->dispatch('refreshOrderStatus');

                                    Mail::to($record->user->email)
                                        ->queue(new OrderRefundedMail($record->user, $record, $data['reason']));
                                })
                                ->disabled(static function (Model $record) {
                                    $items = $record->items->where('has_been_refunded', false);

                                    return $items->count() > 0 ? false : true;
                                })
                                ->hidden(static function (Model $record) {
                                    $items = $record->items->where('has_been_refunded', false);

                                    return $items->count() > 0 ? false : true;
                                }),
                        ])->fullWidth(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                OrderItem::whereHas('shopArticle', function ($query) {
                    $query->where('shop_id', session()->get('shop')->id);
                })->orderBy('created_at', 'DESC')
            )
            ->columns([
                TextColumn::make('status')
                    ->label(__('filament.article_status'))
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
                    ->toggleable()
                    ->searchable()
                    ->alignCenter(),
                TextColumn::make('order.reference')
                    ->label(__('filament.order_reference'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('shopArticle.article.name')
                    ->label(__('filament.article'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('shopArticle.variant.name')
                    ->label(__('filament.variant'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('price')
                    ->label(__('filament.price'))
                    ->toggleable()
                    ->searchable()
                    ->alignCenter()
                    ->formatStateUsing(static function ($state) {
                        return number_format($state, 2, ',', '') . '€';
                    }),
                TextColumn::make('quantity')
                    ->label(__('filament.quantity'))
                    ->toggleable()
                    ->searchable()
                    ->alignCenter(),
                TextColumn::make('total')
                    ->label(__('titles.total'))
                    ->toggleable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('order', function ($query) use ($search) {
                            $query->where('sub_total', $search);
                        });
                    })
                    ->alignCenter()
                    ->getStateUsing(static function (Model $record) {
                        return number_format($record->price * $record->quantity, 2, ',', '') . '€';
                    }),
                TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime('d M Y')
                    ->toggleable()
                    ->searchable()
                    ->alignCenter()
                    ->sortable(),
            ])
            ->defaultGroup(Group::make('order.reference')
                ->label(__('filament.order'))
                ->collapsible())
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (OrderItem $record): string => OrderResource::getUrl('view', [$record->order->reference])),

            ])
            ->poll('10s')
            ->paginated([10, 20, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            /* 'create' => Pages\CreateOrder::route('/create'), */
            /* 'edit' => Pages\EditOrder::route('/{record}/edit'), */
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament.order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.orders');
    }
}
