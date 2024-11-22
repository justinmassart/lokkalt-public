<?php

namespace App\Filament\Dashboard\Resources\ShopResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OwnersRelationManager extends RelationManager
{
    protected static string $relationship = 'owners';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label(__('filament.email'))
                    ->columnSpanFull()
                    ->email()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns([
                TextColumn::make('full_name')
                    ->label(__('filament.name'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->getStateUsing(function (Model $record) {
                        $user = auth()->user();

                        return $record->id === $user->id ? $record->full_name.' (You)' : $record->full_name;
                    }),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('phone')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add an owner')
                    ->modalHeading(function (RelationManager $livewire) {
                        return 'Add an owner to : '.$livewire->getOwnerRecord()->name;
                    })
                    ->modalDescription('An email with the instructions to become an owner of this shop will be sent to the email entered in the next form.')
                    ->modalSubmitActionLabel('Send')
                    ->createAnother(false)
                    ->hidden(function () {
                        return auth()->user()->isEmployee();
                    })
                    ->action(null),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(function () {
                        return auth()->user()->isEmployee();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->hidden(function () {
                        return auth()->user()->isEmployee();
                    }),
            ]);
    }
}
