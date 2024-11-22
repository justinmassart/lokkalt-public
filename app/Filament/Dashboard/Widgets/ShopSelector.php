<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Shop;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ShopSelector extends BaseWidget
{
    protected int|string|array $columnSpan = 2;

    public function getHeading(): string|Htmlable|null
    {
        return ucfirst(__('filament.select_a_shop'));
    }

    // TODO: make widget/button to create a shop if user has no shop

    public static function canView(): bool
    {
        return !session()->has('shop') && session()->has('franchise') && session()->get('franchise')->shops()->exists();
    }

    public function table(Table $table): Table
    {
        $shops = null;

        if (session()->has('franchise')) {
            $shops = Shop::whereFranchiseId(session()->get('franchise')->id);
        } else {
            $shops = Shop::whereHas('owners', function (Builder $query) {
                $query->where('user_id', auth()->user()->id);
            });
        }

        return $table
            ->query(
                $shops
            )
            ->columns([
                Stack::make([
                    TextColumn::make('name')
                        ->label(__('filament.name')),
                    TextColumn::make('address')
                        ->label(__('filament.address')),
                ]),
            ])
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
            ])
            ->paginated(false)
            ->actions([
                Action::make('select')
                    ->label(__('buttons.select'))
                    ->button()
                    ->action(function (Model $record) {
                        return redirect('/change-shop/' . $record->slug);
                    }),
            ]);
    }
}
