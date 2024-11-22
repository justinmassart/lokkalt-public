<?php

namespace App\Filament\Dashboard\Resources;

use App\Filament\Dashboard\Resources\ShopResource\Pages;
use App\Filament\Dashboard\Resources\ShopResource\RelationManagers;
use App\Models\Shop;
use Closure;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ShopResource extends Resource
{
    protected static ?string $model = Shop::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $activeNavigationIcon = 'heroicon-s-building-storefront';

    public static function form(Form $form): Form
    {
        // TODO: ->before and ->after doesn't work on opening_hours inputs
        // TODO: REMOVE COMMENTED LINE FOR VAT AND BA CHECK

        $types = [
            'grocery_store' => 'Grocery Store',
            'clothing_store' => 'Clothing Store',
            'butcher_shop' => 'Butcher Shop',
            'bakery' => 'Bakery',
            'brewery' => 'Brewery',
            'cafe_coffee_shop' => 'Cafe Coffee Shop',
            'bookstore' => 'Bookstore',
            'electronics_store' => 'Electronics Store',
            'jewelry_store' => 'Jewelry Store',
            'toy_store' => 'Toy Store',
            'pet_store' => 'Pet Store',
            'pet_supplies_store' => 'Pet Supplies Store',
            'florist' => 'Florist',
            'furniture_store' => 'Furniture Store',
            'decor_store' => 'Decor Store',
            'art_store' => 'Art Store',
            'sporting_store' => 'Sporting Store',
            'cosmetics_store' => 'Cosmetics Store',
            'beauty_store' => 'Beauty Store',
            'shoe_store' => 'Shoe Store',
            'antique_shop' => 'Antique Shop',
            'craft_store' => 'Craft Store',
            'hardware_store' => 'Hardware Store',
            'stationery_store' => 'Stationery Store',
        ];
        sort($types);

        return $form
            ->columns(4)
            ->schema([
                Section::make(__('filament.actions_section'))
                    ->columnSpan(1)
                    ->schema([
                        Toggle::make('is_active')
                            ->label(__('filament.is_active?'))
                            ->onIcon('heroicon-c-check')
                            ->onColor('success')
                            ->offIcon('heroicon-c-x-mark')
                            ->offColor('danger')
                            ->columnSpanFull()
                            ->required(),
                    ]),
                Section::make(__('filament.shop_infos_section'))
                    ->schema([
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

                                    try {
                                        $uri = "https://controleerbtwnummer.eu/api/validate/$vat.json";

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
                                        Notification::make()
                                            ->title(__('filament.cannot_verify_vat_but_continue'))
                                            ->warning()
                                            ->send();
                                    }
                                },
                            ]),
                        TextInput::make('bank_account')
                            ->label(__('inputs.bankAccount_label'))
                            ->required()
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

                                    try {
                                        $uri = "https://openiban.com/validate/$ba?getBIC=true&validateBankCode=true";
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
                                        Notification::make()
                                            ->title(__('filament.cannot_verify_bank_account_but_continue'))
                                            ->warning()
                                            ->send();
                                    }
                                },
                            ]),
                        Select::make('franchise')
                            ->label(__('titles.franchise'))
                            ->required()
                            ->options(function () {
                                $franchises = auth()->user()->franchises;

                                $options = [];

                                foreach ($franchises as $franchise) {
                                    $options[$franchise->id] = $franchise->name;
                                }

                                return $options;
                            }),
                        // TODO: Check address validity with Google MAPS APIs
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
                            ->live(onBlur: true)
                            /*
                            ->afterStateUpdated(static function ($state, Set $set, Get $get) {
                                $baseUri = 'https://addressvalidation.googleapis.com/v1:validateAddress?key=' . config('services.google.cloud_api_key');
                                $secondUri = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input=' . $state . '&types=geocode&key=' . config('services.google.cloud_api_key');
                                $client = new Client();
                                $country = $get('country');
                                $city = $get('city');
                                $address = [
                                    'regionCode' => $country,
                                    'locality' => $city,
                                    'addressLines' => [$state],
                                ];
                                $response = $client->request('POST', $baseUri, [
                                    'headers' => [
                                        'Content-Type' => 'application/json',
                                    ],
                                    'json' => [
                                        'address' => $address,
                                    ],
                                ]);
                                $data = json_decode($response->getBody(), true);
                            })
                            */
                            ->columnSpanFull(),
                        TextInput::make('email')
                            ->label(__('filament.email'))
                            ->required()
                            ->email(),
                        TextInput::make('phone')
                            ->label(__('filament.phone_number'))
                            ->alphaNum(),
                        // TODO: check to remove line-break or spaces if needed
                        Textarea::make('description')
                            ->label(__('filament.description'))
                            ->hint(static function (string $operation) {
                                return $operation === 'view' ? null : 'Max 325 characters';
                            })
                            ->minLength(5)
                            ->maxLength(325)
                            ->rows(3)
                            ->autosize()
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make(__('filament.images'))
                    ->schema([
                        FileUpload::make('images.url')
                            ->label(__('filament.images'))
                            ->columnSpanFull()
                            ->panelLayout('grid')
                            ->uploadingMessage('Uploading images...')
                            ->maxSize(10240)
                            ->multiple(static function ($state) {
                                return 4 - count($state) === 1 ? false : true;
                            })
                            ->minFiles(1)
                            ->maxFiles(4)
                            ->reorderable()
                            ->appendFiles()
                            ->downloadable()
                            ->storeFiles(false)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                            ])
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9'),
                    ])
                    ->hidden(fn (string $operation): bool => $operation !== 'create'),
                Section::make(__('filament.opening_hours'))
                    ->schema([
                        Fieldset::make('Monday')
                            ->label(__('filament.monday'))
                            ->schema([
                                Repeater::make('opening_hours.monday')
                                    ->hiddenLabel()
                                    ->addActionLabel(__('filament.add_shift'))
                                    ->maxItems(5)
                                    ->reorderable(false)
                                    ->itemLabel(static function ($state) {
                                        return $state['from'] . ' - ' . $state['to'];
                                    })
                                    ->collapsed()
                                    ->columnSpanFull()
                                    ->schema([
                                        TimePicker::make('from')
                                            ->label(__('filament.time_from'))
                                            ->seconds(false)
                                            ->live(onBlur: true)
                                            ->required(static function (Get $get) {
                                                return $get('to') !== null ? true : false;
                                            })
                                            ->before('to'),
                                        TimePicker::make('to')
                                            ->label(__('filament.time_to'))
                                            ->seconds(false)
                                            ->live(onBlur: true)
                                            ->required(static function (Get $get) {
                                                return $get('from') !== null ? true : false;
                                            })
                                            ->after('from'),
                                    ]),
                            ])
                            ->columnSpan(1),
                        Fieldset::make('Tuesday')
                            ->label(__('filament.tuesday'))
                            ->schema([
                                Repeater::make('opening_hours.tuesday')
                                    ->hiddenLabel()
                                    ->addActionLabel(__('filament.add_shift'))
                                    ->maxItems(5)
                                    ->reorderable(false)
                                    ->itemLabel(static function ($state) {
                                        return $state['from'] . ' - ' . $state['to'];
                                    })
                                    ->collapsed()
                                    ->columnSpanFull()
                                    ->schema([
                                        TimePicker::make('from')
                                            ->label(__('filament.time_from'))
                                            ->seconds(false),
                                        TimePicker::make('to')
                                            ->label(__('filament.time_to'))
                                            ->seconds(false),
                                    ]),
                            ])
                            ->columnSpan(1),
                        Fieldset::make('Wednesday')
                            ->label(__('filament.wednesday'))
                            ->schema([
                                Repeater::make('opening_hours.wednesday')
                                    ->hiddenLabel()
                                    ->addActionLabel(__('filament.add_shift'))
                                    ->maxItems(5)
                                    ->reorderable(false)
                                    ->itemLabel(static function ($state) {
                                        return $state['from'] . ' - ' . $state['to'];
                                    })
                                    ->collapsed()
                                    ->columnSpanFull()
                                    ->schema([
                                        TimePicker::make('from')
                                            ->label(__('filament.time_from'))
                                            ->seconds(false),
                                        TimePicker::make('to')
                                            ->label(__('filament.time_to'))
                                            ->seconds(false),
                                    ]),
                            ])
                            ->columnSpan(1),
                        Fieldset::make('Thursday')
                            ->label(__('filament.thursday'))
                            ->schema([
                                Repeater::make('opening_hours.thursday')
                                    ->hiddenLabel()
                                    ->addActionLabel(__('filament.add_shift'))
                                    ->maxItems(5)
                                    ->reorderable(false)
                                    ->itemLabel(static function ($state) {
                                        return $state['from'] . ' - ' . $state['to'];
                                    })
                                    ->collapsed()
                                    ->columnSpanFull()
                                    ->schema([
                                        TimePicker::make('from')
                                            ->label(__('filament.time_from'))
                                            ->seconds(false),
                                        TimePicker::make('to')
                                            ->label(__('filament.time_to'))
                                            ->seconds(false),
                                    ]),
                            ])
                            ->columnSpan(1),
                        Fieldset::make('Friday')
                            ->label(__('filament.friday'))
                            ->schema([
                                Repeater::make('opening_hours.friday')
                                    ->hiddenLabel()
                                    ->addActionLabel(__('filament.add_shift'))
                                    ->maxItems(5)
                                    ->reorderable(false)
                                    ->itemLabel(static function ($state) {
                                        return $state['from'] . ' - ' . $state['to'];
                                    })
                                    ->collapsed()
                                    ->columnSpanFull()
                                    ->schema([
                                        TimePicker::make('from')
                                            ->label(__('filament.time_from'))
                                            ->seconds(false),
                                        TimePicker::make('to')
                                            ->label(__('filament.time_to'))
                                            ->seconds(false),
                                    ]),
                            ])
                            ->columnSpan(1),
                        Fieldset::make('Saturday')
                            ->label(__('filament.saturday'))
                            ->schema([
                                Repeater::make('opening_hours.saturday')
                                    ->hiddenLabel()
                                    ->addActionLabel(__('filament.add_shift'))
                                    ->maxItems(5)
                                    ->reorderable(false)
                                    ->itemLabel(static function ($state) {
                                        return $state['from'] . ' - ' . $state['to'];
                                    })
                                    ->collapsed()
                                    ->columnSpanFull()
                                    ->schema([
                                        TimePicker::make('from')
                                            ->label(__('filament.time_from'))
                                            ->seconds(false),
                                        TimePicker::make('to')
                                            ->label(__('filament.time_to'))
                                            ->seconds(false),
                                    ]),
                            ])
                            ->columnSpan(1),
                        Fieldset::make('Sunday')
                            ->label(__('filament.sunday'))
                            ->schema([
                                Repeater::make('opening_hours.sunday')
                                    ->hiddenLabel()
                                    ->addActionLabel(__('filament.add_shift'))
                                    ->maxItems(5)
                                    ->reorderable(false)
                                    ->itemLabel(static function ($state) {
                                        return $state['from'] . ' - ' . $state['to'];
                                    })
                                    ->collapsed()
                                    ->columnSpanFull()
                                    ->schema([
                                        TimePicker::make('from')
                                            ->label(__('filament.time_from'))
                                            ->seconds(false),
                                        TimePicker::make('to')
                                            ->label(__('filament.time_to'))
                                            ->seconds(false),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        // TODO: check api for validating VAT and BA
        return $table
            ->query(
                Shop::whereFranchiseId(session()->get('franchise')->id)
            )
            ->description(__('filament.shop_table_description'))
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.name'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('is_active')
                    ->label(__('filament.is_active?'))
                    ->toggleable()
                    ->getStateUsing(static function (Model $record) {
                        return $record->is_active == true ? 'Active' : 'Inactive';
                    })
                    ->color(
                        static function ($state) {
                            return $state === 'Active' ? 'success' : 'danger';
                        }
                    ),
                TextColumn::make('email')
                    ->label(__('filament.email'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label(__('filament.phone_number'))
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('address')
                    ->label(__('filament.address'))
                    ->wrap()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('VAT')
                    ->label(__('filament.vat'))
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
                TextColumn::make('owners.full_name')
                    ->label(__('filament.owners'))
                    ->toggleable()
                    ->searchable()
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (Shop $record): string => ShopResource::getUrl('view', [$record->slug])),
                Tables\Actions\EditAction::make()
                    ->url(fn (Shop $record): string => ShopResource::getUrl('edit', [$record->slug])),
            ])
            ->paginated([5, 10, 20, 50])
            ->defaultPaginationPageOption('5');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ArticlesRelationManager::class,
            RelationManagers\ImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShops::route('/'),
            'create' => Pages\CreateShop::route('/create'),
            'view' => Pages\ViewShop::route('/{record}'),
            'edit' => Pages\EditShop::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament.shop');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.shops');
    }
}
