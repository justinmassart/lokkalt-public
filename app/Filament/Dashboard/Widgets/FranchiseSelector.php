<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Franchise;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class FranchiseSelector extends BaseWidget
{
    protected int|string|array $columnSpan = 2;

    protected static ?string $heading = 'Select a franchise';

    // TODO: make widget/button to create a shop if user has no shop

    public static function canView(): bool
    {
        return !session()->has('franchise') && auth()->user()->franchises()->exists();
    }

    public function table(Table $table): Table
    {
        $franchisesIDs = auth()->user()->franchises()->pluck('franchise_id');

        return $table
            ->query(
                Franchise::whereIn('id', $franchisesIDs)
            )
            ->columns([
                Stack::make([
                    TextColumn::make('name'),
                    TextColumn::make('address'),
                ]),
            ])
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
            ])
            ->paginated(false)
            ->actions([
                Action::make('select')
                    ->button()
                    ->action(function (Model $record) {
                        return redirect('/change-franchise/' . $record->id);
                    }),
            ]);
    }
}
