<?php

namespace App\Filament\Dashboard\Resources\ShopResource\RelationManagers;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\DissociateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('firstname')
                    ->label('Firstname')
                    ->required()
                    ->alpha(),
                TextInput::make('lastname')
                    ->label('Lastname')
                    ->required()
                    ->alpha(),
                TextInput::make('email')
                    ->label(__('filament.email'))
                    ->required()
                    ->email(),
                TextInput::make('phone')
                    ->label(__('filament.phone_number'))
                    ->required()
                    ->alphaNum(),
                TextInput::make('address')
                    ->label(__('filament.address'))
                    ->required(),
                Select::make('country')
                    ->label(__('filament.country'))
                    ->required()
                    ->options([
                        'DE' => 'Allemagne',
                        'BE' => 'Belgique',
                        'FR' => 'France',
                        'LU' => 'Luxembourg',
                        'NL' => 'Pays-Bas',
                    ]),
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
                    ->toggleable()
                    ->hidden(function () {
                        return auth()->user()->isEmployee();
                    }),
                TextColumn::make('phone')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->hidden(function () {
                        return auth()->user()->isEmployee();
                    }),
                TextColumn::make('address')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->hidden(function () {
                        return auth()->user()->isEmployee();
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add an employee')
                    ->modalHeading(function (RelationManager $livewire) {
                        return 'Add an employee to : '.$livewire->getOwnerRecord()->name;
                    })
                    ->modalSubmitActionLabel('Add this employee')
                    ->createAnother(false)
                    ->hidden(function () {
                        return auth()->user()->isEmployee();
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['full_name'] = $data['firstname'].' '.$data['lastname'];
                        $data['role'] = 'employee';
                        $data['slug'] = str()->slug($data['firstname'].' '.$data['lastname']).'#'.str()->random(6);
                        while (User::where('slug', $data['slug'])->exists()) {
                            $data['slug'] = str()->slug($data['firstname'].' '.$data['lastname']).'#'.str()->random(6);
                        }
                        $password = str()->random(20);
                        $data['password'] = bcrypt($password);

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(function () {
                        return auth()->user()->isEmployee();
                    }),
                DissociateAction::make('delete')
                    ->label('Delete')
                    ->action(function (Model $record, RelationManager $livewire) {
                        $livewire->getOwnerRecord()->shop_employees()->where('user_id', $record->id)->delete();
                    }),
                /* Tables\Actions\DeleteAction::make()
                    ->hidden(function () {
                        return auth()->user()->isEmployee();
                    }) */
            ]);
    }
}
