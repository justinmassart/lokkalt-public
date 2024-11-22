<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\StockResource\Pages;
use App\Filament\Dashboard\Resources\StockResource\Pages\EditStock;
use App\Filament\Dashboard\Resources\StockResource\Pages\ViewStock;
use App\Filament\Dashboard\Resources\StockResource\RelationManagers;
use App\Models\ShopArticle;
use App\Models\Stock;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $activeNavigationIcon = 'heroicon-s-rectangle-stack';

    public static function getNavigationBadge(): ?string
    {
        $out = ShopArticle::whereShopId(session()->get('shop')->id)
            ->whereHas('stock', function ($query) {
                $query->where('status', 'out');
            })
            ->count();

        $limited = ShopArticle::whereShopId(session()->get('shop')->id)
            ->whereHas('stock', function ($query) {
                $query->where('status', 'limited');
            })
            ->count();

        $string = '';

        if ($out > 0) {
            $string .= $out . ' X';
        }

        if ($out > 0 && $limited = 0) {
            $string .= ' | ';
        }

        if ($limited > 0) {
            $string .= $limited . ' ⚠️';
        }

        return $string;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return __('filament.stocks');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('filament.stock_handling'))
                    ->columns(3)
                    ->schema([
                        TextInput::make('quantity')
                            ->label(__('filament.quantity'))
                            ->numeric()
                            ->minValue(0),
                        Select::make('status')
                            ->label(__('filament.status'))
                            ->options([
                                'in' => 'In stock',
                                'limited' => 'Limited stock',
                                'out' => 'Out of stock',
                            ])
                            ->disabled(),
                        TextInput::make('limited_stock_below')
                            ->label(__('filament.limited_stock_below'))
                            ->numeric(),
                        Textarea::make('comment')
                            ->label(__('filament.comment'))
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $shop = session()->get('shop');

        return $table
            ->query(
                ShopArticle::whereShopId($shop->id)
            )
            ->columns([
                TextColumn::make('article.name')
                    ->label(__('filament.article'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('article.reference')
                    ->label(__('filament.article_reference'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('variant.name')
                    ->label(__('filament.variant'))
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('variant.reference')
                    ->label(__('filament.variant_reference'))
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('stock.quantity')
                    ->label(__('filament.quantity'))
                    ->alignCenter()
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->weight('bold')
                    ->color(static function (Model $record) {
                        switch ($record->stock->status) {
                            case 'in':
                                return 'primary';
                                break;
                            case 'limited':
                                return 'warning';
                                break;
                            case 'out':
                                return 'danger';
                                break;
                        }
                    }),
                TextColumn::make('reserved_stock')
                    ->label(__('titles.reserved_stock'))
                    ->alignCenter()
                    ->weight(function ($state) {
                        return $state > 0 ? 'bold' : null;
                    })
                    ->color(function ($state) {
                        return $state > 0 ? 'warning' : null;
                    })
                    ->getStateUsing(function (Model $record) {
                        return $record->reservedOrders();
                    }),
            ])
            ->groups([
                Group::make('article.name')
                    ->label(__('filament.article_name'))
                    ->collapsible(),
            ])
            ->defaultGroup('article.name')
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Model $record): string => ViewStock::getUrl(['record' => $record->stock])),
                Tables\Actions\EditAction::make()
                    ->url(fn (Model $record): string => EditStock::getUrl(['record' => $record->stock])),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OperationsRelationManager::class,
            RelationManagers\OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStock::route('/create'),
            'view' => Pages\ViewStock::route('/{record}'),
            'edit' => Pages\EditStock::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament.stock');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.stocks');
    }
}
