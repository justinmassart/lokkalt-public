<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\FranchiseResource\Pages;
use App\Filament\Dashboard\Resources\FranchiseResource\RelationManagers;
use App\Filament\Dashboard\Resources\FranchiseResource\RelationManagers\ShopsRelationManager;
use App\Models\Franchise;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FranchiseResource extends Resource
{
    protected static ?string $model = Franchise::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $activeNavigationIcon = 'heroicon-s-building-office-2';

    public static function getModelLabel(): string
    {
        return __('filament.franchise');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.franchises');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label(__('filament.email'))
                            ->required()
                            ->email(),
                        TextInput::make('phone')
                            ->label(__('filament.phone_number'))
                            ->alphaNum(),
                        TextInput::make('VAT')
                            ->label(__('inputs.vat_label'))
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Set $set, HasForms $livewire, TextInput $component) {
                                $state = preg_replace('/[^A-Za-z]/', '', substr($state, 0, 2)) . preg_replace('/[^0-9]/', '', substr($state, 2));
                                $set('VAT', $state);

                                $livewire->validateOnly($component->getStatePath());
                            })
                            ->rules([
                                fn (): Closure => function (string $attribute, $value, Closure $fail) {

                                    if (!$value) {
                                        return;
                                    }

                                    $vat = $value;

                                    $uri = "https://controleerbtwnummer.eu/api/validate/$vat.json";

                                    try {
                                        $client = new Client();
                                        $response = $client->request('GET', $uri, [
                                            'headers' => [
                                                'Content-Type' => 'application/json',
                                            ],
                                        ]);
                                        $data = json_decode($response->getBody(), true);

                                        $valid = $data['valid'];

                                        if (!$valid) {
                                            //$fail(__('titles.wrong_vat_number'));

                                            //return;
                                        }
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                    }
                                },
                            ]),
                        TextInput::make('bank_account')
                            ->label(__('inputs.bankAccount_label'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Set $set, HasForms $livewire, TextInput $component) {
                                $state = preg_replace('/[^A-Za-z]/', '', substr($state, 0, 2)) . preg_replace('/[^0-9]/', '', substr($state, 2));
                                $set('bank_account', $state);

                                $livewire->validateOnly($component->getStatePath());
                            })
                            ->rules([
                                fn (): Closure => function (string $attribute, $value, Closure $fail) {
                                    if (!$value) {
                                        return;
                                    }

                                    $ba = $value;
                                    $uri = "https://openiban.com/validate/$ba?getBIC=true&validateBankCode=true";

                                    try {
                                        $client = new Client();
                                        $response = $client->request('GET', $uri, [
                                            'headers' => [
                                                'Content-Type' => 'application/json',
                                            ],
                                        ]);
                                        $data = json_decode($response->getBody(), true);
                                        $valid = $data['valid'];

                                        if (!$valid) {
                                            //$fail(__('titles.wrong_bank_account'));

                                            //return;
                                        }
                                    } catch (\Throwable $th) {
                                        //throw $th;
                                    }
                                },
                            ]),
                        Select::make('country')
                            ->label(__('filament.country'))
                            ->native(false)
                            ->searchable()
                            ->in(function () {
                                $countries = array_keys(config('locales.supportedCountries'));

                                $options = [];

                                foreach ($countries as $countryCode) {
                                    $options[] = $countryCode;
                                }

                                return $options;
                            })
                            ->options(function () {
                                $countries = config('locales.supportedCountries');

                                $options = [];

                                foreach ($countries as $countryCode => $data) {
                                    $options[$countryCode] = __('countries.' . strtolower($data['name']));
                                }

                                return $options;
                            })
                            ->required(),
                        TextInput::make('postal_code')
                            ->label(__('filament.postal_code'))
                            ->numeric()
                            ->required(),
                        TextInput::make('city')
                            ->label(__('filament.city'))
                            ->required(),
                        TextInput::make('address')
                            ->label(__('filament.address'))
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Franchise::whereHas('owner', function ($query) {
                    $query->where('user_id', auth()->user()->id);
                })
            )
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('filament.email'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->default('N.A')
                    ->label(__('filament.phone'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('VAT')
                    ->label(__('filament.vat'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('bank_account')
                    ->default('N.A')
                    ->label(__('filament.bank_account'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('country')
                    ->label(__('filament.country'))
                    ->size(TextColumnSize::Large)
                    ->getStateUsing(static function (Model $record) {
                        return $record->flag();
                    })
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('city')
                    ->label(__('filament.city'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('postal_code')
                    ->label(__('filament.postal_code'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('address')
                    ->label(__('filament.address'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->date('d M Y')
                    ->label(__('filament.created_at'))
                    ->toggleable()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ShopsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFranchises::route('/'),
            'create' => Pages\CreateFranchise::route('/create'),
            'view' => Pages\ViewFranchise::route('/{record}'),
            'edit' => Pages\EditFranchise::route('/{record}/edit'),
        ];
    }
}
